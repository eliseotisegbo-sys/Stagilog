<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (!file_exists(__DIR__ . '/config.php')) {
    die("Erreur : config.php introuvable dans " . __DIR__);
}
require 'config.php';

if (!isset($pdo)) {
    die("Erreur : la connexion \$pdo n'a pas ete initialisee dans config.php");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: rapport_form.php');
    exit;
}

$nom = trim($_POST['nom'] ?? '');
$prenom = trim($_POST['prenom'] ?? '');

if ($nom === '' || $prenom === '') {
    $_SESSION['erreur'] = "Veuillez renseigner votre nom et votre prenom.";
    header('Location: rapport_form.php');
    exit;
}

try {
    $stmt = $pdo->prepare(
        "SELECT id_eleve FROM dossier WHERE nom_eleve = :nom AND prenom_eleve = :prenom"
    );
    $stmt->execute(['nom' => $nom, 'prenom' => $prenom]);
    $dossiers = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Erreur SQL lors de la recherche du dossier : " . $e->getMessage());
}

if (count($dossiers) === 0) {
    $_SESSION['erreur'] = "Aucun dossier ne correspond a ce nom et prenom.";
    header('Location: rapport_form.php');
    exit;
}

if (count($dossiers) > 1) {
    $_SESSION['erreur'] = "Plusieurs dossiers correspondent a ces informations. Merci de contacter l'administration.";
    header('Location: rapport_form.php');
    exit;
}

$id_eleve = $dossiers[0]['id_eleve'];

if (!isset($_FILES['rapport']) || $_FILES['rapport']['error'] !== UPLOAD_ERR_OK) {
    $_SESSION['erreur'] = "Le fichier n'a pas ete correctement transmis (code erreur : "
        . ($_FILES['rapport']['error'] ?? 'aucun fichier recu') . ").";
    header('Location: rapport_form.php');
    exit;
}

$extension = strtolower(pathinfo($_FILES['rapport']['name'], PATHINFO_EXTENSION));
if ($extension !== 'pdf') {
    $_SESSION['erreur'] = "Seuls les fichiers PDF sont acceptes.";
    header('Location: rapport_form.php');
    exit;
}

$dossierUpload = __DIR__ . '/uploads/rapports/';
if (!is_dir($dossierUpload)) {
    if (!mkdir($dossierUpload, 0755, true) && !is_dir($dossierUpload)) {
        die("Erreur : impossible de creer le dossier $dossierUpload. Verifiez les droits d'ecriture.");
    }
}

if (!is_writable($dossierUpload)) {
    die("Erreur : le dossier $dossierUpload n'est pas accessible en ecriture.");
}

$nomFichier = 'rapport_' . $id_eleve . '_' . time() . '.pdf';
$cheminDestination = $dossierUpload . $nomFichier;

if (!move_uploaded_file($_FILES['rapport']['tmp_name'], $cheminDestination)) {
    $_SESSION['erreur'] = "Erreur lors de l'enregistrement du fichier.";
    header('Location: rapport_form.php');
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE dossier SET rapport = :rapport WHERE id_eleve = :id_eleve");
    $stmt->execute([
        'rapport' => $nomFichier,
        'id_eleve' => $id_eleve
    ]);
} catch (PDOException $e) {
    die("Erreur SQL lors de la mise a jour du dossier : " . $e->getMessage());
}

echo "<p>Votre rapport de fin de stage a bien ete transmis. Merci.</p>";
echo '<a href="rapport_form.php">Retour</a>';
?>