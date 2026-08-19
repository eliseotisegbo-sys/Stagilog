<?php
session_start();
require 'config.php';

$erreur = '';
if (isset($_SESSION['erreur'])) {
    $erreur = $_SESSION['erreur'];
    unset($_SESSION['erreur']);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Depot du rapport de fin de stage</title>
</head>
<body>
    <h1>Depot du rapport de fin de stage</h1>

    <?php if ($erreur): ?>
        <p style="color:red;"><?= htmlspecialchars($erreur) ?></p>
    <?php endif; ?>

    <form action="traiter_rapport.php" method="POST" enctype="multipart/form-data">
        <label for="nom">Nom :</label><br>
        <input type="text" name="nom" id="nom" required><br><br>

        <label for="prenom">Prénoms :</label><br>
        <input type="text" name="prenom" id="prenom" required><br><br>

        <label for="rapport">Rapport fin stage (PDF) :</label><br>
        <input type="file" name="rapport" id="rapport" accept="application/pdf" required><br><br>

        <button type="submit">Envoyer le rapport</button>
    </form>
</body>
</html>
