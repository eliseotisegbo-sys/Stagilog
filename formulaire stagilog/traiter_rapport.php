<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: rapport_form.php');
    exit;
}

$nom = trim($_POST['nom'] ?? '');
$prenom = trim($_POST['prenom'] ?? '');

// Validation des champs
if ($nom === '' || $prenom === '') {
    $_SESSION['erreur'] = "Veuillez renseigner votre nom et votre prenom.";
    header('Location: rapport_form.php');
    exit;
}

// Rechercher l'étudiant par nom ET prénom
$stmt = $pdo->prepare("
    SELECT id_etudiant, nom_etudiant, prenom_etudiant, email_etu, rapport
    FROM etudiants 
    WHERE nom_etudiant = :nom AND prenom_etudiant = :prenom
");
$stmt->execute(['nom' => $nom, 'prenom' => $prenom]);
$etudiants = $stmt->fetchAll();

// Vérifier si un étudiant a été trouvé
if (count($etudiants) === 0) {
    $_SESSION['erreur'] = "Aucun etudiant ne correspond a ce nom et prenom. Verifiez l'orthographe.";
    header('Location: rapport_form.php');
    exit;
}

// Si plusieurs étudiants ont le même nom/prénom (cas rare)
if (count($etudiants) > 1) {
    $_SESSION['erreur'] = "Plusieurs etudiants correspondent a ces informations. Veuillez contacter l'administration avec votre email.";
    header('Location: rapport_form.php');
    exit;
}

$etudiant = $etudiants[0];
$id_etudiant = $etudiant['id_etudiant'];

// Vérifier si un rapport existe déjà
if ($etudiant['rapport']) {
    $_SESSION['erreur'] = "Un rapport a deja ete depose pour cet etudiant. Contactez l'administration pour le remplacer.";
    header('Location: rapport_form.php');
    exit;
}

// Vérifier le fichier rapport
if (!isset($_FILES['rapport']) || $_FILES['rapport']['error'] !== UPLOAD_ERR_OK) {
    $_SESSION['erreur'] = "Le fichier n'a pas ete correctement transmis.";
    header('Location: rapport_form.php');
    exit;
}

$extension = strtolower(pathinfo($_FILES['rapport']['name'], PATHINFO_EXTENSION));
if ($extension !== 'pdf') {
    $_SESSION['erreur'] = "Seuls les fichiers PDF sont acceptes.";
    header('Location: rapport_form.php');
    exit;
}

// Upload du rapport
$dossierUpload = __DIR__ . '/uploads/rapports/';
if (!is_dir($dossierUpload)) {
    mkdir($dossierUpload, 0755, true);
}

$nomFichier = 'rapport_' . $id_etudiant . '_' . time() . '.pdf';
$cheminDestination = $dossierUpload . $nomFichier;

if (!move_uploaded_file($_FILES['rapport']['tmp_name'], $cheminDestination)) {
    $_SESSION['erreur'] = "Erreur lors de l'enregistrement du fichier.";
    header('Location: rapport_form.php');
    exit;
}

// Mise à jour de la base de données
$stmt = $pdo->prepare("UPDATE etudiants SET rapport = :rapport WHERE id_etudiant = :id_etudiant");
$stmt->execute([
    'rapport' => $nomFichier,
    'id_etudiant' => $id_etudiant
]);

$_SESSION['succes'] = "Votre rapport de fin de stage a bien ete transmis. Merci " . htmlspecialchars($prenom . ' ' . $nom) . " !";
header('Location: rapport_form.php');
exit;
?>