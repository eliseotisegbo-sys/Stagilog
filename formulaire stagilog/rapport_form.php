<?php
session_start();
require 'config.php';

$erreur = '';
$succes = '';
if (isset($_SESSION['erreur'])) {
    $erreur = $_SESSION['erreur'];
    unset($_SESSION['erreur']);
}
if (isset($_SESSION['succes'])) {
    $succes = $_SESSION['succes'];
    unset($_SESSION['succes']);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Depot du rapport de fin de stage</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; }
        .error { color: red; padding: 10px; background: #ffeeee; border: 1px solid red; margin-bottom: 15px; }
        .success { color: green; padding: 10px; background: #eeffee; border: 1px solid green; margin-bottom: 15px; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input { width: 100%; padding: 8px; margin-top: 5px; }
        button { margin-top: 20px; padding: 10px 20px; background: #17a2b8; color: white; border: none; cursor: pointer; }
        button:hover { background: #138496; }
        .menu { margin-bottom: 20px; }
        .menu a { margin-right: 15px; text-decoration: none; color: #007bff; }
        .info { background: #fff3cd; padding: 10px; margin-bottom: 15px; border-left: 4px solid #ffc107; }
    </style>
</head>
<body>
<?php
// Ce fichier est obsolète. Le nouveau système de dépôt de rapport offre deux modes :
// - Par école : sélectionner l'école puis le dossier pour un rapport commun
// - Par étudiant : rechercher par nom et prénom pour un rapport individuel
// Redirection vers le nouveau formulaire
header('Location: depot_rapport.php');
exit;
?>

    <h1>Depot du rapport de fin de stage</h1>

    <div class="info">
        <strong>Information :</strong> Veuillez renseigner votre nom et prenom pour deposer votre rapport de stage.
    </div>

    <?php if ($erreur): ?>
        <div class="error"><?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>
    
    <?php if ($succes): ?>
        <div class="success"><?= htmlspecialchars($succes) ?></div>
    <?php endif; ?>

    <form action="traiter_rapport.php" method="POST" enctype="multipart/form-data">
        <label for="nom">Nom :</label>
        <input type="text" name="nom" id="nom" placeholder="Votre nom de famille" required>

        <label for="prenom">Prenom :</label>
        <input type="text" name="prenom" id="prenom" placeholder="Votre prenom" required>

        <label for="rapport">Rapport de fin de stage (PDF) :</label>
        <input type="file" name="rapport" id="rapport" accept="application/pdf" required>

        <button type="submit">Envoyer le rapport</button>
    </form>
</body>
</html>
