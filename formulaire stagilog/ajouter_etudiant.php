<?php
session_start();
require 'config.php';

$erreur = '';
$succes = '';
if (isset($_SESSION['erreur_etudiant'])) {
    $erreur = $_SESSION['erreur_etudiant'];
    unset($_SESSION['erreur_etudiant']);
}
if (isset($_SESSION['succes_etudiant'])) {
    $succes = $_SESSION['succes_etudiant'];
    unset($_SESSION['succes_etudiant']);
}

// Récupérer la liste des dossiers
$stmt = $pdo->query("
    SELECT d.id_dossier, d.annee_academique, d.filiere, e.nom_ecole, d.datedebut, d.datefin
    FROM dossiers d
    INNER JOIN ecoles e ON e.id_ecole = d.id_ecole
    ORDER BY d.created_at DESC
");
$dossiers = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un etudiant</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 700px; margin: 50px auto; padding: 20px; }
        .error { color: red; padding: 10px; background: #ffeeee; border: 1px solid red; margin-bottom: 15px; }
        .success { color: green; padding: 10px; background: #eeffee; border: 1px solid green; margin-bottom: 15px; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input, select { width: 100%; padding: 8px; margin-top: 5px; }
        button { margin-top: 20px; padding: 10px 20px; background: #28a745; color: white; border: none; cursor: pointer; }
        button:hover { background: #218838; }
        .menu { margin-bottom: 20px; }
        .menu a { margin-right: 15px; text-decoration: none; color: #007bff; }
        .info { background: #e7f3ff; padding: 10px; margin-bottom: 15px; border-left: 4px solid #007bff; }
    </style>
</head>
<body>
<?php
// Ce fichier est obsolète. Le nouveau workflow unifié permet de créer un dossier et d'ajouter tous les étudiants en une seule fois.
// Redirection vers le nouveau formulaire
header('Location: creer_dossier_complet.php');
exit;
?>

    <h1>Ajouter un etudiant a un dossier</h1>

    <?php if ($erreur): ?>
        <div class="error"><?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>
    
    <?php if ($succes): ?>
        <div class="success"><?= htmlspecialchars($succes) ?></div>
    <?php endif; ?>

    <?php if (empty($dossiers)): ?>
        <div class="info">
            <strong>Aucun dossier disponible.</strong><br>
            Veuillez d'abord <a href="ajouter_dossier.php">creer un dossier de demande de stage</a>.
        </div>
    <?php else: ?>
        <div class="info">
            <strong>Information :</strong> Selectionnez le dossier de stage puis ajoutez les informations de l'etudiant.
        </div>

        <form action="traiter_etudiant.php" method="POST" enctype="multipart/form-data">
            <label for="id_dossier">Selectionner un dossier :</label>
            <select name="id_dossier" id="id_dossier" required>
                <option value="">-- Choisir un dossier --</option>
                <?php foreach ($dossiers as $d): ?>
                    <option value="<?= $d['id_dossier'] ?>">
                        <?= htmlspecialchars($d['nom_ecole']) ?> - 
                        <?= htmlspecialchars($d['filiere']) ?> - 
                        <?= htmlspecialchars($d['annee_academique']) ?> 
                        (du <?= htmlspecialchars($d['datedebut']) ?> au <?= htmlspecialchars($d['datefin']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="nom_etudiant">Nom de l'etudiant :</label>
            <input type="text" name="nom_etudiant" id="nom_etudiant" required>

            <label for="prenom_etudiant">Prenom de l'etudiant :</label>
            <input type="text" name="prenom_etudiant" id="prenom_etudiant" required>

            <label for="email_etu">Email de l'etudiant :</label>
            <input type="email" name="email_etu" id="email_etu" placeholder="exemple@ecole.edu.sn" required>

            <label for="cv">CV (PDF) :</label>
            <input type="file" name="cv" id="cv" accept="application/pdf" required>

            <button type="submit">Ajouter l'etudiant</button>
        </form>
    <?php endif; ?>
</body>
</html>
