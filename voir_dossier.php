<?php
session_start();

$erreur = '';
if (isset($_SESSION['erreur_recherche'])) {
    $erreur = $_SESSION['erreur_recherche'];
    unset($_SESSION['erreur_recherche']);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Consulter un dossier d'eleve</title>
</head>
<body>
    <h1>Consulter le dossier d'un eleve</h1>

    <?php if ($erreur): ?>
        <p style="color:red;"><?= htmlspecialchars($erreur) ?></p>
    <?php endif; ?>

    <form action="traiter_voir_dossier.php" method="GET">
        <label for="nom_eleve">Nom :</label><br>
        <input type="text" name="nom_eleve" id="nom_eleve" required><br><br>

        <label for="prenom_eleve">Prenoms :</label><br>
        <input type="text" name="prenom_eleve" id="prenom_eleve" required><br><br>

        <button type="submit">Rechercher</button>
    </form>
</body>
</html>