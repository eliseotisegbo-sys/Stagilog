<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ajouter_dossier.php');
    exit;
}

$id_ecole = filter_input(INPUT_POST, 'id_ecole', FILTER_VALIDATE_INT);
$annee_academique = trim($_POST['annee_academique'] ?? '');
$filiere = trim($_POST['filiere'] ?? '');
$datedebut = trim($_POST['datedebut'] ?? '');
$datefin = trim($_POST['datefin'] ?? '');

// Validation des champs obligatoires
if (!$id_ecole || $annee_academique === '' || $filiere === '' || $datedebut === '' || $datefin === '') {
    $_SESSION['erreur_dossier'] = "Veuillez remplir tous les champs obligatoires.";
    header('Location: ajouter_dossier.php');
    exit;
}

// Vérifier que l'école existe
$stmt = $pdo->prepare("SELECT id_ecole FROM ecoles WHERE id_ecole = :id_ecole");
$stmt->execute(['id_ecole' => $id_ecole]);
if (!$stmt->fetch()) {
    $_SESSION['erreur_dossier'] = "Ecole invalide.";
    header('Location: ajouter_dossier.php');
    exit;
}

// Vérifier les dates
if ($datefin < $datedebut) {
    $_SESSION['erreur_dossier'] = "La date de fin ne peut pas etre anterieure a la date de debut.";
    header('Location: ajouter_dossier.php');
    exit;
}

// Gestion de la lettre de demande (optionnelle)
$nomFichierLettre = null;
if (isset($_FILES['lettredemande']) && $_FILES['lettredemande']['error'] === UPLOAD_ERR_OK) {
    $extLettre = strtolower(pathinfo($_FILES['lettredemande']['name'], PATHINFO_EXTENSION));
    
    if ($extLettre !== 'pdf') {
        $_SESSION['erreur_dossier'] = "La lettre de demande doit etre au format PDF.";
        header('Location: ajouter_dossier.php');
        exit;
    }
    
    $dossierUploadLettre = __DIR__ . '/uploads/lettres/';
    if (!is_dir($dossierUploadLettre)) {
        mkdir($dossierUploadLettre, 0755, true);
    }
    
    $identifiant = uniqid();
    $nomFichierLettre = 'lettre_' . $identifiant . '.pdf';
    
    if (!move_uploaded_file($_FILES['lettredemande']['tmp_name'], $dossierUploadLettre . $nomFichierLettre)) {
        $_SESSION['erreur_dossier'] = "Erreur lors de l'enregistrement de la lettre de demande.";
        header('Location: ajouter_dossier.php');
        exit;
    }
}

// Insérer le dossier
$stmt = $pdo->prepare(
    "INSERT INTO dossiers
        (annee_academique, filiere, lettredemande, datedebut, datefin, id_ecole)
     VALUES
        (:annee_academique, :filiere, :lettredemande, :datedebut, :datefin, :id_ecole)"
);
$stmt->execute([
    'annee_academique' => $annee_academique,
    'filiere' => $filiere,
    'lettredemande' => $nomFichierLettre,
    'datedebut' => $datedebut,
    'datefin' => $datefin,
    'id_ecole' => $id_ecole
]);

$id_dossier = $pdo->lastInsertId();

$_SESSION['succes_dossier'] = "Le dossier a bien ete cree (ID: $id_dossier). Vous pouvez maintenant y ajouter des etudiants.";
header('Location: ajouter_etudiant.php');
exit;
?>