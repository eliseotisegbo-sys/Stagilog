<?php
session_start();
require 'config.php';

$erreur = '';
if (isset($_SESSION['erreur_dossier'])) {
    $erreur = $_SESSION['erreur_dossier'];
    unset($_SESSION['erreur_dossier']);
}

$stmt = $pdo->query("SELECT id_ecole, nom_ecole FROM ecole ORDER BY nom_ecole");
$ecoles = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Depot du dossier d'un eleve</title>
</head>
<body>
    <h1>Depot du dossier d'un eleve</h1>

    <?php if ($erreur): ?>
        <p style="color:red;"><?= htmlspecialchars($erreur) ?></p>
    <?php endif; ?>

    <form action="traiter_dossier.php" method="POST" enctype="multipart/form-data">
        <label for="id_ecole">Ecole :</label><br>
        <select name="id_ecole" id="id_ecole" required>
            <option value="">-- Selectionner une ecole --</option>
            <?php foreach ($ecoles as $ecole): ?>
                <option value="<?= $ecole['id_ecole'] ?>">
                    <?= htmlspecialchars($ecole['nom_ecole']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label for="nom_eleve">Nom :</label><br>
        <input type="text" name="nom_eleve" id="nom_eleve" required><br><br>

        <label for="prenom_eleve">Prenoms :</label><br>
        <input type="text" name="prenom_eleve" id="prenom_eleve" required><br><br>

        <label for="annee_academique">Annee academique :</label><br>
        <input type="text" name="annee_academique" id="annee_academique" placeholder="ex: 2025" required><br><br>

        <label for="filiere">Filiere :</label><br>
        <input type="text" name="filiere" id="filiere" required><br><br>

        <label for="datedebut">Date de debut du stage :</label><br>
        <input type="date" name="datedebut" id="datedebut" required><br><br>

        <label for="datefin">Date de fin du stage :</label><br>
        <input type="date" name="datefin" id="datefin" required><br><br>

        <label for="cv">CV (PDF) :</label><br>
        <input type="file" name="cv" id="cv" accept="application/pdf" required><br><br>

        <label for="lettre_motivation">Lettre de motivation (PDF) :</label><br>
        <input type="file" name="lettre_motivation" id="lettre_motivation" accept="application/pdf" required><br><br>

        <button type="submit">Enregistrer le dossier</button>
    </form>
</body>
</html>