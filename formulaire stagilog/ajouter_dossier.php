<?php
session_start();
require 'config.php';

$erreur = '';
$succes = '';
if (isset($_SESSION['erreur_dossier'])) {
    $erreur = $_SESSION['erreur_dossier'];
    unset($_SESSION['erreur_dossier']);
}
if (isset($_SESSION['succes_dossier'])) {
    $succes = $_SESSION['succes_dossier'];
    unset($_SESSION['succes_dossier']);
}

$stmt = $pdo->query("SELECT id_ecole, nom_ecole FROM ecoles ORDER BY nom_ecole");
$ecoles = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Creer une demande de stage</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; }
        .error { color: red; padding: 10px; background: #ffeeee; border: 1px solid red; margin-bottom: 15px; }
        .success { color: green; padding: 10px; background: #eeffee; border: 1px solid green; margin-bottom: 15px; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input, select { width: 100%; padding: 8px; margin-top: 5px; }
        button { margin-top: 20px; padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer; }
        button:hover { background: #0056b3; }
        .menu { margin-bottom: 20px; }
        .menu a { margin-right: 15px; text-decoration: none; color: #007bff; }
    </style>
</head>
<body>
<?php
// Ce fichier est obsolète. Le nouveau workflow unifié permet de créer un dossier et d'ajouter tous les étudiants en une seule fois.
// Redirection vers le nouveau formulaire
header('Location: creer_dossier_complet.php');
exit;
?>

    <h1>Creer une demande de stage</h1>

    <?php if ($erreur): ?>
        <div class="error"><?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>
    
    <?php if ($succes): ?>
        <div class="success"><?= htmlspecialchars($succes) ?></div>
    <?php endif; ?>

    <form action="traiter_dossier.php" method="POST" enctype="multipart/form-data">
        <label for="id_ecole">Ecole :</label>
        <select name="id_ecole" id="id_ecole" required>
            <option value="">-- Selectionner une ecole --</option>
            <?php foreach ($ecoles as $ecole): ?>
                <option value="<?= $ecole['id_ecole'] ?>">
                    <?= htmlspecialchars($ecole['nom_ecole']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="annee_academique">Annee academique :</label>
        <input type="text" name="annee_academique" id="annee_academique" placeholder="ex: 2025-2026" required>

        <label for="filiere">Filiere :</label>
        <input type="text" name="filiere" id="filiere" placeholder="ex: Genie Informatique" required>

        <label for="datedebut">Date de debut du stage :</label>
        <input type="date" name="datedebut" id="datedebut" required>

        <label for="datefin">Date de fin du stage :</label>
        <input type="date" name="datefin" id="datefin" required>

        <label for="lettredemande">Lettre de demande (PDF - optionnel) :</label>
        <input type="file" name="lettredemande" id="lettredemande" accept="application/pdf">

        <button type="submit">Creer le dossier</button>
    </form>
</body>
</html>