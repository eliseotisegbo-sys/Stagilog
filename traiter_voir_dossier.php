<?php
session_start();
require 'config.php';

$nom_eleve = trim($_GET['nom_eleve'] ?? '');
$prenom_eleve = trim($_GET['prenom_eleve'] ?? '');

if ($nom_eleve === '' || $prenom_eleve === '') {
    $_SESSION['erreur_recherche'] = "Veuillez renseigner le nom et le prenom.";
    header('Location: voir_dossier.php');
    exit;
}

$stmt = $pdo->prepare(
    "SELECT d.*, e.nom_ecole
     FROM dossier d
     INNER JOIN ecole e ON e.id_ecole = d.id_ecole
     WHERE d.nom_eleve = :nom_eleve AND d.prenom_eleve = :prenom_eleve"
);
$stmt->execute(['nom_eleve' => $nom_eleve, 'prenom_eleve' => $prenom_eleve]);
$dossiers = $stmt->fetchAll();

if (count($dossiers) === 0) {
    $_SESSION['erreur_recherche'] = "Aucun dossier ne correspond a ce nom et prenom.";
    header('Location: voir_dossier.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dossier de l'eleve</title>
</head>
<body>
    <h1>Resultat de la recherche</h1>
    <p><a href="voir_dossier.php">Nouvelle recherche</a></p>

    <?php foreach ($dossiers as $dossier): ?>
    <div style="border:1px solid #ccc; padding:15px; margin-bottom:20px;">
        <h2><?= htmlspecialchars($dossier['nom_eleve']) ?> <?= htmlspecialchars($dossier['prenom_eleve']) ?></h2>
        <p><strong>Ecole :</strong> <?= htmlspecialchars($dossier['nom_ecole']) ?></p>
        <p><strong>Filiere :</strong> <?= htmlspecialchars($dossier['filiere']) ?></p>
        <p><strong>Annee academique :</strong> <?= htmlspecialchars($dossier['annee_academique']) ?></p>
        <p><strong>Periode de stage :</strong> du <?= htmlspecialchars($dossier['datedebut']) ?> au <?= htmlspecialchars($dossier['datefin']) ?></p>

        <p><strong>CV :</strong>
            <?php if ($dossier['cv']): ?>
                <a href="voir_fichier.php?id=<?= $dossier['id_eleve'] ?>&type=cv" target="_blank">Consulter le PDF</a>
            <?php else: ?>
                Non fourni
            <?php endif; ?>
        </p>

        <p><strong>Lettre de motivation :</strong>
            <?php if ($dossier['lettre_motivation']): ?>
                <a href="voir_fichier.php?id=<?= $dossier['id_eleve'] ?>&type=lettre" target="_blank">Consulter le PDF</a>
            <?php else: ?>
                Non fournie
            <?php endif; ?>
        </p>

        <p><strong>Rapport de fin de stage :</strong>
            <?php if ($dossier['rapport']): ?>
                <a href="voir_fichier.php?id=<?= $dossier['id_eleve'] ?>&type=rapport" target="_blank">Consulter le PDF</a>
            <?php else: ?>
                Pas encore depose
            <?php endif; ?>
        </p>
    </div>
    <?php endforeach; ?>
</body>
</html>