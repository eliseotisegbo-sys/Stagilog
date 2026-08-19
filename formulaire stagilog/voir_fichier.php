<?php
require 'config.php';

$id_etudiant = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$type = $_GET['type'] ?? '';

$typesAutorises = [
    'cv'      => ['colonne' => 'cv',      'dossier' => 'uploads/cv/'],
    'rapport' => ['colonne' => 'rapport', 'dossier' => 'uploads/rapports/'],
];

if (!$id_etudiant || !isset($typesAutorises[$type])) {
    die("Demande invalide.");
}

$colonne = $typesAutorises[$type]['colonne'];
$dossierFichiers = $typesAutorises[$type]['dossier'];

$stmt = $pdo->prepare("SELECT $colonne AS fichier FROM etudiants WHERE id_etudiant = :id_etudiant");
$stmt->execute(['id_etudiant' => $id_etudiant]);
$resultat = $stmt->fetch();

if (!$resultat || !$resultat['fichier']) {
    die("Fichier introuvable.");
}

$chemin = __DIR__ . '/' . $dossierFichiers . basename($resultat['fichier']);

if (!file_exists($chemin)) {
    die("Fichier introuvable sur le serveur.");
}

header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="' . basename($chemin) . '"');
header('Content-Length: ' . filesize($chemin));
readfile($chemin);
exit;
?>