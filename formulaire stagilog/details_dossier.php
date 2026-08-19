<?php
require 'config.php';

$id_dossier = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id_dossier) {
    echo "<p>Dossier invalide.</p>";
    exit;
}

// Récupérer les informations du dossier
$stmt = $pdo->prepare("
    SELECT 
        d.*,
        e.nom_ecole,
        e.adresse_ecole,
        e.num_ecole,
        e.mail
    FROM dossiers d
    INNER JOIN ecoles e ON e.id_ecole = d.id_ecole
    WHERE d.id_dossier = :id
");
$stmt->execute(['id' => $id_dossier]);
$dossier = $stmt->fetch();

if (!$dossier) {
    echo "<p>Dossier introuvable.</p>";
    exit;
}

// Récupérer les étudiants du dossier
$stmt = $pdo->prepare("
    SELECT * FROM etudiants WHERE id_dossier = :id ORDER BY nom_etudiant, prenom_etudiant
");
$stmt->execute(['id' => $id_dossier]);
$etudiants = $stmt->fetchAll();
?>
<style>
    .detail-section { margin: 20px 0; padding: 15px; background: #f8f9fa; border-radius: 5px; }
    .detail-section h3 { color: #007bff; margin-bottom: 10px; }
    .detail-row { padding: 8px 0; }
    .detail-row strong { display: inline-block; width: 180px; }
    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
    th { background: #007bff; color: white; }
    .statut-badge { padding: 5px 12px; border-radius: 15px; font-size: 0.85em; font-weight: bold; }
    .statut-en_attente { background: #fff3cd; color: #856404; }
    .statut-valide { background: #d4edda; color: #155724; }
    .statut-refuse { background: #f8d7da; color: #721c24; }
    .lien-fichier { color: #007bff; text-decoration: none; }
    .lien-fichier:hover { text-decoration: underline; }
</style>

<h2>Détails du dossier #<?= $dossier['id_dossier'] ?></h2>

<div class="detail-section">
    <h3>📋 Informations du dossier</h3>
    <div class="detail-row"><strong>Statut :</strong> 
        <span class="statut-badge statut-<?= $dossier['statut'] ?>">
            <?php
            switch($dossier['statut']) {
                case 'en_attente': echo '⏳ En attente'; break;
                case 'valide': echo '✅ Validé'; break;
                case 'refuse': echo '❌ Refusé'; break;
            }
            ?>
        </span>
    </div>
    <div class="detail-row"><strong>Année académique :</strong> <?= htmlspecialchars($dossier['annee_academique']) ?></div>
    <div class="detail-row"><strong>Filière :</strong> <?= htmlspecialchars($dossier['filiere']) ?></div>
    <div class="detail-row"><strong>Date de début :</strong> <?= date('d/m/Y', strtotime($dossier['datedebut'])) ?></div>
    <div class="detail-row"><strong>Date de fin :</strong> <?= date('d/m/Y', strtotime($dossier['datefin'])) ?></div>
    <div class="detail-row"><strong>Date de création :</strong> <?= date('d/m/Y H:i', strtotime($dossier['created_at'])) ?></div>
    <?php if ($dossier['lettredemande']): ?>
        <div class="detail-row">
            <strong>Lettre de demande :</strong> 
            <a href="voir_lettre.php?id=<?= $dossier['id_dossier'] ?>" target="_blank" class="lien-fichier">📄 Consulter le PDF</a>
        </div>
    <?php endif; ?>
</div>

<div class="detail-section">
    <h3>🏫 Informations de l'école</h3>
    <div class="detail-row"><strong>Nom :</strong> <?= htmlspecialchars($dossier['nom_ecole']) ?></div>
    <div class="detail-row"><strong>Adresse :</strong> <?= htmlspecialchars($dossier['adresse_ecole'] ?? 'Non renseignée') ?></div>
    <div class="detail-row"><strong>Téléphone :</strong> <?= htmlspecialchars($dossier['num_ecole'] ?? 'Non renseigné') ?></div>
    <div class="detail-row"><strong>Email :</strong> <?= htmlspecialchars($dossier['mail'] ?? 'Non renseigné') ?></div>
</div>

<div class="detail-section">
    <h3>👨‍🎓 Étudiants (<?= count($etudiants) ?>)</h3>
    
    <?php if (empty($etudiants)): ?>
        <p>Aucun étudiant dans ce dossier.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>CV</th>
                    <th>Rapport</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($etudiants as $etudiant): ?>
                    <tr>
                        <td><?= htmlspecialchars($etudiant['nom_etudiant']) ?></td>
                        <td><?= htmlspecialchars($etudiant['prenom_etudiant']) ?></td>
                        <td><?= htmlspecialchars($etudiant['email_etu']) ?></td>
                        <td>
                            <a href="voir_fichier.php?id=<?= $etudiant['id_etudiant'] ?>&type=cv" target="_blank" class="lien-fichier">
                                📄 CV
                            </a>
                        </td>
                        <td>
                            <?php if ($etudiant['rapport']): ?>
                                <a href="voir_fichier.php?id=<?= $etudiant['id_etudiant'] ?>&type=rapport" target="_blank" class="lien-fichier">
                                    📑 Rapport
                                </a>
                            <?php else: ?>
                                <span style="color: #dc3545;">Non déposé</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
