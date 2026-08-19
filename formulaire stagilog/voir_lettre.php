<?php
require 'config.php';

$id_dossier = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id_dossier) {
    die("Demande invalide.");
}

$stmt = $pdo->prepare("SELECT lettredemande FROM dossiers WHERE id_dossier = :id");
$stmt->execute(['id' => $id_dossier]);
$resultat = $stmt->fetch();

if (!$resultat || !$resultat['lettredemande']) {
    die("Lettre de demande introuvable.");
}

$chemin = __DIR__ . '/uploads/lettres/' . basename($resultat['lettredemande']);

if (!file_exists($chemin)) {
    die("Fichier introuvable sur le serveur.");
}

header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="' . basename($chemin) . '"');
header('Content-Length: ' . filesize($chemin));
readfile($chemin);
exit;
?>
