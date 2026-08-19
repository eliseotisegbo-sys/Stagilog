<?php
require 'config.php';

$id_eleve = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$type = $_GET['type'] ?? '';

$typesAutorises = [
    'cv'      => ['colonne' => 'cv',                'dossier' => 'uploads/cv/'],
    'lettre'  => ['colonne' => 'lettre_motivation',  'dossier' => 'uploads/lettres/'],
    'rapport' => ['colonne' => 'rapport',            'dossier' => 'uploads/rapports/'],
];

if (!$id_eleve || !isset($typesAutorises[$type])) {
    die("Demande invalide.");
}

$colonne = $typesAutorises[$type]['colonne'];
$dossierFichiers = $typesAutorises[$type]['dossier'];

$stmt = $pdo->prepare("SELECT $colonne AS fichier FROM dossier WHERE id_eleve = :id_eleve");
$stmt->execute(['id_eleve' => $id_eleve]);
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