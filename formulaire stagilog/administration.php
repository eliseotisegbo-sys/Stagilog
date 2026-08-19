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

// Récupérer tous les dossiers avec leurs statistiques
$stmt = $pdo->query("
    SELECT 
        d.id_dossier,
        d.annee_academique,
        d.filiere,
        d.datedebut,
        d.datefin,
        d.statut,
        d.lettredemande,
        d.created_at,
        e.nom_ecole,
        COUNT(et.id_etudiant) AS nb_etudiants,
        SUM(CASE WHEN et.rapport IS NOT NULL THEN 1 ELSE 0 END) AS nb_rapports
    FROM dossiers d
    INNER JOIN ecoles e ON e.id_ecole = d.id_ecole
    LEFT JOIN etudiants et ON et.id_dossier = d.id_dossier
    GROUP BY d.id_dossier, d.annee_academique, d.filiere, d.datedebut, d.datefin, d.statut, d.lettredemande, d.created_at, e.nom_ecole
    ORDER BY d.created_at DESC
");
$dossiers = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Gestion des dossiers</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; margin-bottom: 10px; }
        .menu { margin-bottom: 20px; padding: 10px; background: #f8f9fa; border-radius: 5px; }
        .menu a { margin-right: 15px; text-decoration: none; color: #007bff; }
        .error { color: #721c24; background: #f8d7da; border: 1px solid #f5c6cb; padding: 12px; margin: 15px 0; border-radius: 5px; }
        .success { color: #155724; background: #d4edda; border: 1px solid #c3e6cb; padding: 12px; margin: 15px 0; border-radius: 5px; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin: 20px 0; }
        .stat-card { padding: 20px; border-radius: 8px; text-align: center; color: white; }
        .stat-card h3 { font-size: 2em; margin-bottom: 5px; }
        .stat-card p { font-size: 0.9em; }
        .stat-total { background: #007bff; }
        .stat-attente { background: #ffc107; color: #000; }
        .stat-valide { background: #28a745; }
        .stat-refuse { background: #dc3545; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #007bff; color: white; position: sticky; top: 0; }
        tr:hover { background: #f8f9fa; }
        .statut-badge { display: inline-block; padding: 5px 12px; border-radius: 15px; font-size: 0.85em; font-weight: bold; }
        .statut-en_attente { background: #fff3cd; color: #856404; }
        .statut-valide { background: #d4edda; color: #155724; }
        .statut-refuse { background: #f8d7da; color: #721c24; }
        .btn { padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; font-size: 0.9em; margin: 2px; }
        .btn-voir { background: #17a2b8; color: white; text-decoration: none; display: inline-block; }
        .btn-voir:hover { background: #138496; }
        .btn-valider { background: #28a745; color: white; }
        .btn-valider:hover { background: #218838; }
        .btn-refuser { background: #dc3545; color: white; }
        .btn-refuser:hover { background: #c82333; }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 1000; }
        .modal-content { background: white; max-width: 900px; margin: 50px auto; padding: 30px; border-radius: 10px; max-height: 80vh; overflow-y: auto; }
        .modal-close { float: right; font-size: 28px; font-weight: bold; cursor: pointer; color: #999; }
        .modal-close:hover { color: #333; }
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

        <h1>🏛️ Administration - Gestion des demandes de stage</h1>

        <?php if ($erreur): ?>
            <div class="error">❌ <?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>
        
        <?php if ($succes): ?>
            <div class="success">✅ <?= htmlspecialchars($succes) ?></div>
        <?php endif; ?>

        <!-- Statistiques -->
        <div class="stats">
            <?php
            $total = count($dossiers);
            $en_attente = count(array_filter($dossiers, fn($d) => $d['statut'] === 'en_attente'));
            $valides = count(array_filter($dossiers, fn($d) => $d['statut'] === 'valide'));
            $refuses = count(array_filter($dossiers, fn($d) => $d['statut'] === 'refuse'));
            ?>
            <div class="stat-card stat-total">
                <h3><?= $total ?></h3>
                <p>Total dossiers</p>
            </div>
            <div class="stat-card stat-attente">
                <h3><?= $en_attente ?></h3>
                <p>En attente</p>
            </div>
            <div class="stat-card stat-valide">
                <h3><?= $valides ?></h3>
                <p>Validés</p>
            </div>
            <div class="stat-card stat-refuse">
                <h3><?= $refuses ?></h3>
                <p>Refusés</p>
            </div>
        </div>

        <!-- Table des dossiers -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>École</th>
                    <th>Filière</th>
                    <th>Année</th>
                    <th>Période</th>
                    <th>Étudiants</th>
                    <th>Rapports</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($dossiers)): ?>
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 30px; color: #999;">
                            Aucun dossier pour le moment
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($dossiers as $dossier): ?>
                        <tr>
                            <td><strong>#<?= $dossier['id_dossier'] ?></strong></td>
                            <td><?= htmlspecialchars($dossier['nom_ecole']) ?></td>
                            <td><?= htmlspecialchars($dossier['filiere']) ?></td>
                            <td><?= htmlspecialchars($dossier['annee_academique']) ?></td>
                            <td style="font-size: 0.85em;">
                                <?= date('d/m/Y', strtotime($dossier['datedebut'])) ?><br>
                                au <?= date('d/m/Y', strtotime($dossier['datefin'])) ?>
                            </td>
                            <td style="text-align: center;">
                                <strong><?= $dossier['nb_etudiants'] ?></strong>
                            </td>
                            <td style="text-align: center;">
                                <?= $dossier['nb_rapports'] ?> / <?= $dossier['nb_etudiants'] ?>
                            </td>
                            <td>
                                <span class="statut-badge statut-<?= $dossier['statut'] ?>">
                                    <?php
                                    switch($dossier['statut']) {
                                        case 'en_attente': echo '⏳ En attente'; break;
                                        case 'valide': echo '✅ Validé'; break;
                                        case 'refuse': echo '❌ Refusé'; break;
                                    }
                                    ?>
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-voir" onclick="voirDetails(<?= $dossier['id_dossier'] ?>)">
                                    👁️ Voir
                                </button>
                                <?php if ($dossier['statut'] === 'en_attente'): ?>
                                    <form action="valider_dossier.php" method="POST" style="display: inline;">
                                        <input type="hidden" name="id_dossier" value="<?= $dossier['id_dossier'] ?>">
                                        <input type="hidden" name="action" value="valider">
                                        <button type="submit" class="btn btn-valider" onclick="return confirm('Valider ce dossier ?')">
                                            ✅ Valider
                                        </button>
                                    </form>
                                    <form action="valider_dossier.php" method="POST" style="display: inline;">
                                        <input type="hidden" name="id_dossier" value="<?= $dossier['id_dossier'] ?>">
                                        <input type="hidden" name="action" value="refuser">
                                        <button type="submit" class="btn btn-refuser" onclick="return confirm('Refuser ce dossier ?')">
                                            ❌ Refuser
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal pour voir les détails -->
    <div id="modalDetails" class="modal">
        <div class="modal-content">
            <span class="modal-close" onclick="fermerModal()">&times;</span>
            <div id="contenuModal">Chargement...</div>
        </div>
    </div>

    <script>
        function voirDetails(id) {
            document.getElementById('modalDetails').style.display = 'block';
            document.getElementById('contenuModal').innerHTML = 'Chargement...';
            
            fetch('details_dossier.php?id=' + id)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('contenuModal').innerHTML = html;
                });
        }

        function fermerModal() {
            document.getElementById('modalDetails').style.display = 'none';
        }

        // Fermer en cliquant en dehors
        window.onclick = function(event) {
            const modal = document.getElementById('modalDetails');
            if (event.target === modal) {
                fermerModal();
            }
        }
    </script>
</body>
</html>
