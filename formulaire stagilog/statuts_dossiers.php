<?php
session_start();
require 'config.php';

$erreur = '';
$succes = '';
$ecole_selectionnee = null;
$dossiers = [];

if (isset($_SESSION['erreur'])) {
    $erreur = $_SESSION['erreur'];
    unset($_SESSION['erreur']);
}
if (isset($_SESSION['succes'])) {
    $succes = $_SESSION['succes'];
    unset($_SESSION['succes']);
}

// Récupérer toutes les écoles
$ecoles = $pdo->query("SELECT id_ecole, nom_ecole FROM ecoles ORDER BY nom_ecole")->fetchAll();

// Si une école est sélectionnée
if (isset($_GET['id_ecole'])) {
    $id_ecole = filter_input(INPUT_GET, 'id_ecole', FILTER_VALIDATE_INT);
    
    if ($id_ecole) {
        // Récupérer les informations de l'école
        $stmt = $pdo->prepare("SELECT nom_ecole FROM ecoles WHERE id_ecole = :id");
        $stmt->execute(['id' => $id_ecole]);
        $ecole_selectionnee = $stmt->fetch();
        
        if ($ecole_selectionnee) {
            // Récupérer tous les dossiers de cette école avec statistiques
            $stmt = $pdo->prepare("
                SELECT 
                    d.id_dossier,
                    d.annee_academique,
                    d.filiere,
                    d.datedebut,
                    d.datefin,
                    d.lettredemande,
                    d.statut,
                    d.created_at,
                    COUNT(e.id_etudiant) as nb_etudiants,
                    SUM(CASE WHEN e.rapport IS NOT NULL THEN 1 ELSE 0 END) as nb_rapports
                FROM dossiers d
                LEFT JOIN etudiants e ON e.id_dossier = d.id_dossier
                WHERE d.id_ecole = :id_ecole
                GROUP BY d.id_dossier
                ORDER BY d.created_at DESC
            ");
            $stmt->execute(['id_ecole' => $id_ecole]);
            $dossiers = $stmt->fetchAll();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statuts des dossiers - STAGILOG</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: Arial, sans-serif; 
            background: #f5f5f5; 
            padding: 20px; 
        }
        .container { 
            max-width: 1200px; 
            margin: 0 auto; 
            background: white; 
            padding: 30px; 
            border-radius: 10px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
        }
        h1 { 
            color: #333; 
            margin-bottom: 10px; 
        }
        .menu { 
            margin-bottom: 20px; 
            padding: 10px; 
            background: #f8f9fa; 
            border-radius: 5px; 
        }
        .menu a { 
            margin-right: 15px; 
            text-decoration: none; 
            color: #007bff; 
        }
        .error { 
            color: #721c24; 
            background: #f8d7da; 
            border: 1px solid #f5c6cb; 
            padding: 12px; 
            margin: 15px 0; 
            border-radius: 5px; 
        }
        .success { 
            color: #155724; 
            background: #d4edda; 
            border: 1px solid #c3e6cb; 
            padding: 12px; 
            margin: 15px 0; 
            border-radius: 5px; 
        }
        .info { 
            background: #d1ecf1; 
            border-left: 4px solid #0dcaf0; 
            padding: 12px; 
            margin: 15px 0; 
        }
        label { 
            display: block; 
            margin-top: 15px; 
            font-weight: bold; 
        }
        input, select { 
            width: 100%; 
            padding: 10px; 
            margin-top: 5px; 
            border: 1px solid #ddd; 
            border-radius: 5px; 
        }
        .btn { 
            padding: 12px 30px; 
            margin: 15px 5px 0 0; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            font-weight: bold; 
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary { 
            background: #007bff; 
            color: white; 
        }
        .btn-back { 
            background: #6c757d; 
            color: white; 
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 20px 0; 
        }
        th, td { 
            padding: 12px; 
            text-align: left; 
            border-bottom: 1px solid #ddd; 
        }
        th { 
            background: #007bff; 
            color: white; 
        }
        tr:hover { 
            background: #f8f9fa; 
        }
        .statut { 
            display: inline-block; 
            padding: 5px 15px; 
            border-radius: 15px; 
            font-weight: bold; 
            font-size: 0.9em;
        }
        .statut-valide { 
            background: #d4edda; 
            color: #155724; 
        }
        .statut-en_attente { 
            background: #fff3cd; 
            color: #856404; 
        }
        .statut-refuse { 
            background: #f8d7da; 
            color: #721c24; 
        }
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .stat-card h3 {
            font-size: 2.5em;
            margin-bottom: 5px;
        }
        .stat-card p {
            opacity: 0.9;
        }
        .dossier-card {
            border: 2px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            margin: 15px 0;
            background: #f8f9fa;
        }
        .dossier-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 2px solid #ddd;
        }
        .dossier-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }
        .info-item {
            background: white;
            padding: 10px;
            border-radius: 5px;
        }
        .info-item strong {
            display: block;
            color: #666;
            font-size: 0.85em;
            margin-bottom: 5px;
        }
        .etudiants-list {
            background: white;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .etudiant-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .etudiant-item:last-child {
            border-bottom: none;
        }
        .rapport-badge {
            padding: 3px 10px;
            border-radius: 10px;
            font-size: 0.85em;
            font-weight: bold;
        }
        .rapport-ok {
            background: #d4edda;
            color: #155724;
        }
        .rapport-non {
            background: #f8d7da;
            color: #721c24;
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }
        .empty-state .icon {
            font-size: 5em;
            margin-bottom: 20px;
            opacity: 0.3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="menu">
            <a href="index.php">🏠 Accueil</a>
            <a href="creer_dossier_complet.php">📝 Créer un dossier</a>
            <a href="depot_rapport.php">📄 Dépôt de rapport</a>
            <a href="statuts_dossiers.php">📊 Statuts des dossiers</a>
            <a href="administration.php">🏛️ Administration</a>
        </div>

        <h1>📊 Statuts des dossiers</h1>

        <?php if ($erreur): ?>
            <div class="error">❌ <?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>
        
        <?php if ($succes): ?>
            <div class="success">✅ <?= htmlspecialchars($succes) ?></div>
        <?php endif; ?>

        <?php if (!$ecole_selectionnee): ?>
            <!-- Sélection de l'école -->
            <div class="info">
                <strong>ℹ️ Information :</strong> Sélectionnez une école pour consulter tous ses dossiers et leur statut.
            </div>

            <form action="statuts_dossiers.php" method="GET">
                <label for="id_ecole">Sélectionnez votre école :</label>
                <select name="id_ecole" id="id_ecole" required>
                    <option value="">-- Choisir une école --</option>
                    <?php foreach ($ecoles as $ecole): ?>
                        <option value="<?= $ecole['id_ecole'] ?>">
                            <?= htmlspecialchars($ecole['nom_ecole']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" class="btn btn-primary">🔍 Consulter les dossiers</button>
            </form>

        <?php else: ?>
            <!-- Affichage des dossiers -->
            <a href="statuts_dossiers.php" class="btn btn-back">← Changer d'école</a>

            <h2 style="margin-top: 20px; color: #667eea;">
                🏫 <?= htmlspecialchars($ecole_selectionnee['nom_ecole']) ?>
            </h2>

            <?php if (empty($dossiers)): ?>
                <div class="empty-state">
                    <div class="icon">📋</div>
                    <h2>Aucun dossier</h2>
                    <p>Cette école n'a pas encore créé de dossier de stage.</p>
                    <a href="creer_dossier_complet.php" class="btn btn-primary" style="margin-top: 20px;">
                        ➕ Créer un dossier
                    </a>
                </div>
            <?php else: ?>
                <!-- Statistiques globales -->
                <div class="stats-cards">
                    <?php
                    $total = count($dossiers);
                    $valides = count(array_filter($dossiers, fn($d) => $d['statut'] === 'valide'));
                    $en_attente = count(array_filter($dossiers, fn($d) => $d['statut'] === 'en_attente'));
                    $total_etudiants = array_sum(array_column($dossiers, 'nb_etudiants'));
                    $total_rapports = array_sum(array_column($dossiers, 'nb_rapports'));
                    ?>
                    <div class="stat-card">
                        <h3><?= $total ?></h3>
                        <p>Dossiers totaux</p>
                    </div>
                    <div class="stat-card" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                        <h3><?= $valides ?></h3>
                        <p>Dossiers validés</p>
                    </div>
                    <div class="stat-card" style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);">
                        <h3><?= $en_attente ?></h3>
                        <p>En attente</p>
                    </div>
                    <div class="stat-card" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);">
                        <h3><?= $total_etudiants ?></h3>
                        <p>Étudiants</p>
                    </div>
                    <div class="stat-card" style="background: linear-gradient(135deg, #6c757d 0%, #495057 100%);">
                        <h3><?= $total_rapports ?>/<?= $total_etudiants ?></h3>
                        <p>Rapports déposés</p>
                    </div>
                </div>

                <!-- Liste des dossiers -->
                <h3 style="margin-top: 30px; margin-bottom: 15px;">📋 Tous les dossiers</h3>

                <?php foreach ($dossiers as $dossier):
                    // Récupérer les étudiants de ce dossier
                    $stmt = $pdo->prepare("
                        SELECT id_etudiant, nom_etudiant, prenom_etudiant, email_etu, cv, rapport
                        FROM etudiants
                        WHERE id_dossier = :id
                        ORDER BY nom_etudiant, prenom_etudiant
                    ");
                    $stmt->execute(['id' => $dossier['id_dossier']]);
                    $etudiants = $stmt->fetchAll();
                ?>
                    <div class="dossier-card">
                        <div class="dossier-header">
                            <div>
                                <h3 style="color: #333; margin-bottom: 5px;">
                                    Dossier #<?= $dossier['id_dossier'] ?> - <?= htmlspecialchars($dossier['filiere']) ?>
                                </h3>
                                <span style="color: #666; font-size: 0.9em;">
                                    Créé le <?= date('d/m/Y à H:i', strtotime($dossier['created_at'])) ?>
                                </span>
                            </div>
                            <span class="statut statut-<?= $dossier['statut'] ?>">
                                <?php
                                switch($dossier['statut']) {
                                    case 'en_attente': echo '⏳ En attente'; break;
                                    case 'valide': echo '✅ Validé'; break;
                                    case 'refuse': echo '❌ Refusé'; break;
                                }
                                ?>
                            </span>
                        </div>

                        <div class="dossier-info">
                            <div class="info-item">
                                <strong>Année académique</strong>
                                <?= htmlspecialchars($dossier['annee_academique']) ?>
                            </div>
                            <div class="info-item">
                                <strong>Période de stage</strong>
                                <?= date('d/m/Y', strtotime($dossier['datedebut'])) ?>
                                au
                                <?= date('d/m/Y', strtotime($dossier['datefin'])) ?>
                            </div>
                            <div class="info-item">
                                <strong>Nombre d'étudiants</strong>
                                <?= $dossier['nb_etudiants'] ?>
                            </div>
                            <div class="info-item">
                                <strong>Rapports déposés</strong>
                                <?= $dossier['nb_rapports'] ?>/<?= $dossier['nb_etudiants'] ?>
                            </div>
                            <?php if ($dossier['lettredemande']): ?>
                            <div class="info-item">
                                <strong>Lettre de demande</strong>
                                <a href="voir_lettre.php?id=<?= $dossier['id_dossier'] ?>" target="_blank" style="color: #007bff;">
                                    📄 Consulter
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($etudiants)): ?>
                            <div class="etudiants-list">
                                <strong style="display: block; margin-bottom: 10px; color: #333;">
                                    👥 Liste des étudiants (<?= count($etudiants) ?>)
                                </strong>
                                <?php foreach ($etudiants as $etu): ?>
                                    <div class="etudiant-item">
                                        <div>
                                            <strong><?= htmlspecialchars($etu['nom_etudiant']) ?> <?= htmlspecialchars($etu['prenom_etudiant']) ?></strong>
                                            <br>
                                            <span style="color: #666; font-size: 0.9em;"><?= htmlspecialchars($etu['email_etu']) ?></span>
                                        </div>
                                        <div style="text-align: right;">
                                            <?php if ($etu['cv']): ?>
                                                <a href="voir_fichier.php?id=<?= $etu['id_etudiant'] ?>&type=cv" target="_blank" style="color: #007bff; font-size: 0.9em; margin-right: 10px;">
                                                    📄 CV
                                                </a>
                                            <?php endif; ?>
                                            
                                            <?php if ($etu['rapport']): ?>
                                                <a href="voir_fichier.php?id=<?= $etu['id_etudiant'] ?>&type=rapport" target="_blank" style="color: #007bff; font-size: 0.9em; margin-right: 10px;">
                                                    📑 Rapport
                                                </a>
                                                <span class="rapport-badge rapport-ok">✓ Déposé</span>
                                            <?php else: ?>
                                                <span class="rapport-badge rapport-non">✗ Non déposé</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>
