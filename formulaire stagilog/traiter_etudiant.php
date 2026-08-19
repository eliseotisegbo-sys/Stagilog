<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ajouter_etudiant.php');
    exit;
}

$id_dossier = filter_input(INPUT_POST, 'id_dossier', FILTER_VALIDATE_INT);
$nom_etudiant = trim($_POST['nom_etudiant'] ?? '');
$prenom_etudiant = trim($_POST['prenom_etudiant'] ?? '');
$email_etu = trim($_POST['email_etu'] ?? '');

// Validation des champs obligatoires
if (!$id_dossier || $nom_etudiant === '' || $prenom_etudiant === '' || $email_etu === '') {
    $_SESSION['erreur_etudiant'] = "Tous les champs sont obligatoires.";
    header('Location: ajouter_etudiant.php');
    exit;
}

// Valider le format email
if (!filter_var($email_etu, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['erreur_etudiant'] = "L'adresse email n'est pas valide.";
    header('Location: ajouter_etudiant.php');
    exit;
}

// Vérifier que le dossier existe
$stmt = $pdo->prepare("SELECT id_dossier FROM dossiers WHERE id_dossier = :id_dossier");
$stmt->execute(['id_dossier' => $id_dossier]);
if (!$stmt->fetch()) {
    $_SESSION['erreur_etudiant'] = "Dossier invalide.";
    header('Location: ajouter_etudiant.php');
    exit;
}

// Vérifier que l'email est unique
$stmt = $pdo->prepare("SELECT id_etudiant FROM etudiants WHERE email_etu = :email");
$stmt->execute(['email' => $email_etu]);
if ($stmt->fetch()) {
    $_SESSION['erreur_etudiant'] = "Cet email est deja utilise par un autre etudiant.";
    header('Location: ajouter_etudiant.php');
    exit;
}

// Vérifier le fichier CV
if (!isset($_FILES['cv']) || $_FILES['cv']['error'] !== UPLOAD_ERR_OK) {
    $_SESSION['erreur_etudiant'] = "Le CV est obligatoire.";
    header('Location: ajouter_etudiant.php');
    exit;
}

$extCv = strtolower(pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION));
if ($extCv !== 'pdf') {
    $_SESSION['erreur_etudiant'] = "Le CV doit etre au format PDF.";
    header('Location: ajouter_etudiant.php');
    exit;
}

// Upload du CV
$dossierUploadCv = __DIR__ . '/uploads/cv/';
if (!is_dir($dossierUploadCv)) {
    mkdir($dossierUploadCv, 0755, true);
}

$identifiant = uniqid();
$nomFichierCv = 'cv_' . $identifiant . '_' . preg_replace('/[^a-zA-Z0-9]/', '_', $nom_etudiant) . '.pdf';

if (!move_uploaded_file($_FILES['cv']['tmp_name'], $dossierUploadCv . $nomFichierCv)) {
    $_SESSION['erreur_etudiant'] = "Erreur lors de l'enregistrement du CV.";
    header('Location: ajouter_etudiant.php');
    exit;
}

// Insertion de l'étudiant
$stmt = $pdo->prepare("
    INSERT INTO etudiants (nom_etudiant, prenom_etudiant, email_etu, cv, id_dossier)
    VALUES (:nom, :prenom, :email, :cv, :id_dossier)
");
$stmt->execute([
    'nom' => $nom_etudiant,
    'prenom' => $prenom_etudiant,
    'email' => $email_etu,
    'cv' => $nomFichierCv,
    'id_dossier' => $id_dossier
]);

$_SESSION['succes_etudiant'] = "L'etudiant " . htmlspecialchars($prenom_etudiant . ' ' . $nom_etudiant) . " a ete ajoute avec succes !";
header('Location: ajouter_etudiant.php');
exit;
?>
