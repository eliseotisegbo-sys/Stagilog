<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ajouter_dossier.php');
    exit;
}

$id_ecole = filter_input(INPUT_POST, 'id_ecole', FILTER_VALIDATE_INT);
$nom_eleve = trim($_POST['nom_eleve'] ?? '');
$prenom_eleve = trim($_POST['prenom_eleve'] ?? '');
$annee_academique = trim($_POST['annee_academique'] ?? '');
$filiere = trim($_POST['filiere'] ?? '');
$datedebut = trim($_POST['datedebut'] ?? '');
$datefin = trim($_POST['datefin'] ?? '');

if (!$id_ecole || $nom_eleve === '' || $prenom_eleve === '' || $annee_academique === ''
    || $filiere === '' || $datedebut === '' || $datefin === '') {
    $_SESSION['erreur_dossier'] = "Veuillez remplir tous les champs obligatoires, y compris l'ecole.";
    header('Location: ajouter_dossier.php');
    exit;
}

$stmt = $pdo->prepare("SELECT id_ecole FROM ecole WHERE id_ecole = :id_ecole");
$stmt->execute(['id_ecole' => $id_ecole]);
if (!$stmt->fetch()) {
    $_SESSION['erreur_dossier'] = "Ecole invalide.";
    header('Location: ajouter_dossier.php');
    exit;
}

if ($datefin < $datedebut) {
    $_SESSION['erreur_dossier'] = "La date de fin ne peut pas etre anterieure a la date de debut.";
    header('Location: ajouter_dossier.php');
    exit;
}

if (!isset($_FILES['cv']) || $_FILES['cv']['error'] !== UPLOAD_ERR_OK
    || !isset($_FILES['lettre_motivation']) || $_FILES['lettre_motivation']['error'] !== UPLOAD_ERR_OK) {
    $_SESSION['erreur_dossier'] = "Le CV et la lettre de motivation sont obligatoires.";
    header('Location: ajouter_dossier.php');
    exit;
}

$extCv = strtolower(pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION));
$extLettre = strtolower(pathinfo($_FILES['lettre_motivation']['name'], PATHINFO_EXTENSION));

if ($extCv !== 'pdf' || $extLettre !== 'pdf') {
    $_SESSION['erreur_dossier'] = "Le CV et la lettre de motivation doivent etre au format PDF.";
    header('Location: ajouter_dossier.php');
    exit;
}

$dossierUploadCv = __DIR__ . '/uploads/cv/';
$dossierUploadLettre = __DIR__ . '/uploads/lettres/';
if (!is_dir($dossierUploadCv)) {
    mkdir($dossierUploadCv, 0755, true);
}
if (!is_dir($dossierUploadLettre)) {
    mkdir($dossierUploadLettre, 0755, true);
}

$identifiant = uniqid();
$nomFichierCv = 'cv_' . $identifiant . '.pdf';
$nomFichierLettre = 'lettre_' . $identifiant . '.pdf';

if (!move_uploaded_file($_FILES['cv']['tmp_name'], $dossierUploadCv . $nomFichierCv)) {
    $_SESSION['erreur_dossier'] = "Erreur lors de l'enregistrement du CV.";
    header('Location: ajouter_dossier.php');
    exit;
}

if (!move_uploaded_file($_FILES['lettre_motivation']['tmp_name'], $dossierUploadLettre . $nomFichierLettre)) {
    $_SESSION['erreur_dossier'] = "Erreur lors de l'enregistrement de la lettre de motivation.";
    header('Location: ajouter_dossier.php');
    exit;
}

$stmt = $pdo->prepare(
    "INSERT INTO dossier
        (nom_eleve, prenom_eleve, annee_academique, filiere, cv, lettre_motivation, datedebut, datefin, rapport, id_ecole)
     VALUES
        (:nom_eleve, :prenom_eleve, :annee_academique, :filiere, :cv, :lettre_motivation, :datedebut, :datefin, NULL, :id_ecole)"
);
$stmt->execute([
    'nom_eleve' => $nom_eleve,
    'prenom_eleve' => $prenom_eleve,
    'annee_academique' => $annee_academique,
    'filiere' => $filiere,
    'cv' => $nomFichierCv,
    'lettre_motivation' => $nomFichierLettre,
    'datedebut' => $datedebut,
    'datefin' => $datefin,
    'id_ecole' => $id_ecole
]);

echo "<p>Le dossier de l'eleve a bien ete enregistre.</p>";
echo '<a href="ajouter_dossier.php">Ajouter un autre dossier</a>';
?>