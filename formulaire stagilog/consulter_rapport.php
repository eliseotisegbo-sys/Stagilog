<?php
session_start();
require 'config.php';

$erreur = '';
$resultat = null;

if (isset($_SESSION['erreur_consultation'])) {
    $erreur = $_SESSION['erreur_consultation'];
    unset($_SESSION['erreur_consultation']);
}

// Si un formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['nom']) && isset($_GET['prenom'])) {
    $nom = trim($_GET['nom'] ?? '');
    $prenom = trim($_GET['prenom'] ?? '');
    
    if ($nom !== '' && $prenom !== '') {
        // Rechercher l'étudiant avec ses informations complètes
        $stmt = $pdo->prepare("
            SELECT 
                e.id_etudiant,
                e.nom_etudiant,
                e.prenom_etudiant,
                e.email_etu,
                e.cv,
                e.rapport,
                d.annee_academique,
                d.filiere,
                d.datedebut,
                d.datefin,
                ec.nom_ecole,
                ec.id_ecole
            FROM etudiants e
            INNER JOIN dossiers d ON d.id_dossier = e.id_dossier
            INNER JOIN ecoles ec ON ec.id_ecole = d.id_ecole
            WHERE e.nom_etudiant = :nom AND e.prenom_etudiant = :prenom
        ");
        $stmt->execute(['nom' => $nom, 'prenom' => $prenom]);
        $resultat = $stmt->fetch();
        
        if (!$resultat) {
            $erreur = "Aucun etudiant trouve avec ce nom et prenom.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Consulter les rapports de stage</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .error { color: red; padding: 10px; background: #ffeeee; border: 1px solid red; margin-bottom: 15px; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input { width: 100%; padding: 8px; margin-top: 5px; }
        button { margin-top: 20px; padding: 10px 20px; background: #6c757d; color: white; border: none; cursor: pointer; }
        button:hover { background: #545b62; }
        .menu { margin-bottom: 20px; }
        .menu a { margin-right: 15px; text-decoration: none; color: #007bff; }
        .info { background: #d1ecf1; padding: 10px; margin-bottom: 15px; border-left: 4px solid #17a2b8; }
        .fiche-etudiant { border: 2px solid #28a745; padding: 20px; margin-top: 20px; background: #f8f9fa; }
        .fiche-etudiant h2 { color: #28a745; margin-top: 0; }
        .fiche-etudiant p { margin: 10px 0; }
        .fiche-etudiant strong { display: inline-block; width: 180px; }
        .fichier-link { color: #007bff; text-decoration: none; font-weight: bold; }
        .fichier-link:hover { text-decoration: underline; }
        .no-rapport { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>
<?php
// Ce fichier est obsolète. Le nouveau système permet de consulter les dossiers via :
// - depot_rapport.php : recherche par nom/prénom avec affichage du dossier complet
// - statuts_dossiers.php : consultation de tous les dossiers d'une école
// Redirection vers depot_rapport.php (mode étudiant)
header('Location: depot_rapport.php?mode=etudiant');
exit;
?>

    <h1>Consulter le dossier d'un etudiant</h1>

    <div class="info">
        <strong>Pour les ecoles :</strong> Recherchez un etudiant par son nom et prenom pour consulter son dossier et son rapport de stage.
    </div>

    <?php if ($erreur): ?>
        <div class="error"><?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>

    <form action="consulter_rapport.php" method="GET">
        <label for="nom">Nom de l'etudiant :</label>
        <input type="text" name="nom" id="nom" value="<?= htmlspecialchars($_GET['nom'] ?? '') ?>" required>

        <label for="prenom">Prenom de l'etudiant :</label>
        <input type="text" name="prenom" id="prenom" value="<?= htmlspecialchars($_GET['prenom'] ?? '') ?>" required>

        <button type="submit">Rechercher</button>
    </form>

    <?php if ($resultat): ?>
        <div class="fiche-etudiant">
            <h2>Fiche de l'etudiant</h2>
            
            <p><strong>Nom :</strong> <?= htmlspecialchars($resultat['nom_etudiant']) ?></p>
            <p><strong>Prenom :</strong> <?= htmlspecialchars($resultat['prenom_etudiant']) ?></p>
            <p><strong>Email :</strong> <?= htmlspecialchars($resultat['email_etu']) ?></p>
            
            <hr>
            
            <p><strong>Ecole :</strong> <?= htmlspecialchars($resultat['nom_ecole']) ?></p>
            <p><strong>Filiere :</strong> <?= htmlspecialchars($resultat['filiere']) ?></p>
            <p><strong>Annee academique :</strong> <?= htmlspecialchars($resultat['annee_academique']) ?></p>
            <p><strong>Periode de stage :</strong> du <?= htmlspecialchars($resultat['datedebut']) ?> au <?= htmlspecialchars($resultat['datefin']) ?></p>
            
            <hr>
            
            <p><strong>CV :</strong> 
                <?php if ($resultat['cv']): ?>
                    <a href="voir_fichier.php?id=<?= $resultat['id_etudiant'] ?>&type=cv" target="_blank" class="fichier-link">
                        📄 Consulter le CV (PDF)
                    </a>
                <?php else: ?>
                    <span class="no-rapport">Non fourni</span>
                <?php endif; ?>
            </p>
            
            <p><strong>Rapport de stage :</strong> 
                <?php if ($resultat['rapport']): ?>
                    <a href="voir_fichier.php?id=<?= $resultat['id_etudiant'] ?>&type=rapport" target="_blank" class="fichier-link">
                        📑 Consulter le rapport (PDF)
                    </a>
                <?php else: ?>
                    <span class="no-rapport">Pas encore depose</span>
                <?php endif; ?>
            </p>
        </div>
    <?php endif; ?>
</body>
</html>
