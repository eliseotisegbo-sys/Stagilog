<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: creer_dossier_complet.php');
    exit;
}

// Récupération des données du dossier
$id_ecole = filter_input(INPUT_POST, 'id_ecole', FILTER_VALIDATE_INT);
$annee_academique = trim($_POST['annee_academique'] ?? '');
$filiere = trim($_POST['filiere'] ?? '');
$datedebut = trim($_POST['datedebut'] ?? '');
$datefin = trim($_POST['datefin'] ?? '');
$etudiants = $_POST['etudiants'] ?? [];

// Validations
if (!$id_ecole || $annee_academique === '' || $filiere === '' || $datedebut === '' || $datefin === '') {
    $_SESSION['erreur'] = "Tous les champs du dossier sont obligatoires.";
    header('Location: creer_dossier_complet.php');
    exit;
}

if (empty($etudiants)) {
    $_SESSION['erreur'] = "Vous devez ajouter au moins un étudiant.";
    header('Location: creer_dossier_complet.php');
    exit;
}

if ($datefin < $datedebut) {
    $_SESSION['erreur'] = "La date de fin ne peut pas être antérieure à la date de début.";
    header('Location: creer_dossier_complet.php');
    exit;
}

// Vérifier que l'école existe
$stmt = $pdo->prepare("SELECT id_ecole FROM ecoles WHERE id_ecole = :id_ecole");
$stmt->execute(['id_ecole' => $id_ecole]);
if (!$stmt->fetch()) {
    $_SESSION['erreur'] = "École invalide.";
    header('Location: creer_dossier_complet.php');
    exit;
}

try {
    // Démarrer une transaction
    $pdo->beginTransaction();
    
    // 1. Upload de la lettre de demande (optionnel)
    $nomFichierLettre = null;
    if (isset($_FILES['lettredemande']) && $_FILES['lettredemande']['error'] === UPLOAD_ERR_OK) {
        $extLettre = strtolower(pathinfo($_FILES['lettredemande']['name'], PATHINFO_EXTENSION));
        
        if ($extLettre !== 'pdf') {
            throw new Exception("La lettre de demande doit être au format PDF.");
        }
        
        $dossierUploadLettre = __DIR__ . '/uploads/lettres/';
        if (!is_dir($dossierUploadLettre)) {
            mkdir($dossierUploadLettre, 0755, true);
        }
        
        $identifiant = uniqid();
        $nomFichierLettre = 'lettre_' . $identifiant . '.pdf';
        
        if (!move_uploaded_file($_FILES['lettredemande']['tmp_name'], $dossierUploadLettre . $nomFichierLettre)) {
            throw new Exception("Erreur lors de l'enregistrement de la lettre de demande.");
        }
    }
    
    // 2. Insérer le dossier avec statut 'en_attente'
    $stmt = $pdo->prepare("
        INSERT INTO dossiers (annee_academique, filiere, lettredemande, datedebut, datefin, id_ecole, statut)
        VALUES (:annee_academique, :filiere, :lettredemande, :datedebut, :datefin, :id_ecole, 'en_attente')
    ");
    $stmt->execute([
        'annee_academique' => $annee_academique,
        'filiere' => $filiere,
        'lettredemande' => $nomFichierLettre,
        'datedebut' => $datedebut,
        'datefin' => $datefin,
        'id_ecole' => $id_ecole
    ]);
    
    $id_dossier = $pdo->lastInsertId();
    
    // 3. Créer le dossier pour les CVs
    $dossierUploadCv = __DIR__ . '/uploads/cv/';
    if (!is_dir($dossierUploadCv)) {
        mkdir($dossierUploadCv, 0755, true);
    }
    
    // 4. Insérer tous les étudiants
    $emails_utilises = [];
    $nb_etudiants = 0;
    
    foreach ($etudiants as $index => $etudiant) {
        $nom = trim($etudiant['nom'] ?? '');
        $prenom = trim($etudiant['prenom'] ?? '');
        $email = trim($etudiant['email'] ?? '');
        
        // Validation
        if ($nom === '' || $prenom === '' || $email === '') {
            throw new Exception("Tous les champs sont obligatoires pour l'étudiant #$index.");
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("L'email de l'étudiant #$index n'est pas valide.");
        }
        
        // Vérifier l'unicité de l'email
        if (in_array($email, $emails_utilises)) {
            throw new Exception("L'email $email est utilisé plusieurs fois dans ce formulaire.");
        }
        
        $stmt = $pdo->prepare("SELECT id_etudiant FROM etudiants WHERE email_etu = :email");
        $stmt->execute(['email' => $email]);
        if ($stmt->fetch()) {
            throw new Exception("L'email $email est déjà utilisé par un autre étudiant.");
        }
        
        $emails_utilises[] = $email;
        
        // Upload du CV
        if (!isset($_FILES['etudiants']['name'][$index]['cv']) || 
            $_FILES['etudiants']['error'][$index]['cv'] !== UPLOAD_ERR_OK) {
            throw new Exception("Le CV est obligatoire pour l'étudiant $prenom $nom.");
        }
        
        $cvTmpName = $_FILES['etudiants']['tmp_name'][$index]['cv'];
        $cvName = $_FILES['etudiants']['name'][$index]['cv'];
        $extCv = strtolower(pathinfo($cvName, PATHINFO_EXTENSION));
        
        if ($extCv !== 'pdf') {
            throw new Exception("Le CV de $prenom $nom doit être au format PDF.");
        }
        
        $nomFichierCv = 'cv_' . uniqid() . '_' . preg_replace('/[^a-zA-Z0-9]/', '_', $nom) . '.pdf';
        
        if (!move_uploaded_file($cvTmpName, $dossierUploadCv . $nomFichierCv)) {
            throw new Exception("Erreur lors de l'enregistrement du CV de $prenom $nom.");
        }
        
        // Insérer l'étudiant
        $stmt = $pdo->prepare("
            INSERT INTO etudiants (nom_etudiant, prenom_etudiant, email_etu, cv, id_dossier)
            VALUES (:nom, :prenom, :email, :cv, :id_dossier)
        ");
        $stmt->execute([
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'cv' => $nomFichierCv,
            'id_dossier' => $id_dossier
        ]);
        
        $nb_etudiants++;
    }
    
    // Valider la transaction
    $pdo->commit();
    
    $_SESSION['succes'] = "Demande de stage créée avec succès ! $nb_etudiants étudiant(s) ajouté(s). Votre dossier est en attente de validation.";
    header('Location: creer_dossier_complet.php');
    exit;
    
} catch (Exception $e) {
    // Annuler la transaction en cas d'erreur
    $pdo->rollBack();
    
    $_SESSION['erreur'] = "Erreur : " . $e->getMessage();
    header('Location: creer_dossier_complet.php');
    exit;
}
?>
