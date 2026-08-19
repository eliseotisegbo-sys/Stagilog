<?php
session_start();
require 'config.php';

$erreur = '';
$succes = '';
$mode = $_GET['mode'] ?? 'choix'; // choix, ecole, etudiant
$etudiant = null;
$etudiants_dossier = [];

if (isset($_SESSION['erreur'])) {
    $erreur = $_SESSION['erreur'];
    unset($_SESSION['erreur']);
}
if (isset($_SESSION['succes'])) {
    $succes = $_SESSION['succes'];
    unset($_SESSION['succes']);
}

// MODE ÉCOLE : Recherche par école
if ($mode === 'ecole' && isset($_GET['id_ecole'])) {
    $id_ecole = filter_input(INPUT_GET, 'id_ecole', FILTER_VALIDATE_INT);
    
    if ($id_ecole) {
        // Récupérer les dossiers VALIDÉS de cette école
        $stmt = $pdo->prepare("
            SELECT d.id_dossier, d.filiere, d.annee_academique, d.datedebut, d.datefin,
                   COUNT(e.id_etudiant) as nb_etudiants
            FROM dossiers d
            LEFT JOIN etudiants e ON e.id_dossier = d.id_dossier
            WHERE d.id_ecole = :id_ecole AND d.statut = 'valide'
            GROUP BY d.id_dossier
            ORDER BY d.created_at DESC
        ");
        $stmt->execute(['id_ecole' => $id_ecole]);
        $dossiers = $stmt->fetchAll();
    }
}

// MODE ÉCOLE : Affichage des étudiants d'un dossier
if ($mode === 'ecole' && isset($_GET['id_dossier'])) {
    $id_dossier = filter_input(INPUT_GET, 'id_dossier', FILTER_VALIDATE_INT);
    
    if ($id_dossier) {
        // Vérifier que le dossier est validé
        $stmt = $pdo->prepare("SELECT statut FROM dossiers WHERE id_dossier = :id");
        $stmt->execute(['id' => $id_dossier]);
        $dossier_info = $stmt->fetch();
        
        if ($dossier_info && $dossier_info['statut'] === 'valide') {
            // Récupérer les étudiants sans rapport
            $stmt = $pdo->prepare("
                SELECT id_etudiant, nom_etudiant, prenom_etudiant, email_etu
                FROM etudiants
                WHERE id_dossier = :id AND rapport IS NULL
                ORDER BY nom_etudiant, prenom_etudiant
            ");
            $stmt->execute(['id' => $id_dossier]);
            $etudiants_dossier = $stmt->fetchAll();
        }
    }
}

// MODE ÉTUDIANT : Recherche par nom/prénom
if ($mode === 'etudiant' && isset($_GET['nom']) && isset($_GET['prenom'])) {
    $nom = trim($_GET['nom']);
    $prenom = trim($_GET['prenom']);
    
    if ($nom && $prenom) {
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
                d.statut,
                ec.nom_ecole
            FROM etudiants e
            INNER JOIN dossiers d ON d.id_dossier = e.id_dossier
            INNER JOIN ecoles ec ON ec.id_ecole = d.id_ecole
            WHERE e.nom_etudiant = :nom AND e.prenom_etudiant = :prenom
        ");
        $stmt->execute(['nom' => $nom, 'prenom' => $prenom]);
        $etudiant = $stmt->fetch();
        
        if (!$etudiant) {
            $erreur = "Aucun étudiant trouvé avec ce nom et prénom.";
        }
    }
}

// Récupérer toutes les écoles pour le mode école
$ecoles = $pdo->query("SELECT id_ecole, nom_ecole FROM ecoles ORDER BY nom_ecole")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dépôt de rapport</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; margin-bottom: 10px; }
        .menu { margin-bottom: 20px; padding: 10px; background: #f8f9fa; border-radius: 5px; }
        .menu a { margin-right: 15px; text-decoration: none; color: #007bff; }
        .error { color: #721c24; background: #f8d7da; border: 1px solid #f5c6cb; padding: 12px; margin: 15px 0; border-radius: 5px; }
        .success { color: #155724; background: #d4edda; border: 1px solid #c3e6cb; padding: 12px; margin: 15px 0; border-radius: 5px; }
        .info { background: #fff3cd; border-left: 4px solid #ffc107; padding: 12px; margin: 15px 0; }
        .warning { background: #f8d7da; border-left: 4px solid #dc3545; padding: 12px; margin: 15px 0; }
        .mode-selection { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 30px 0; }
        .mode-card { padding: 30px; background: #f8f9fa; border-radius: 10px; text-align: center; cursor: pointer; transition: all 0.3s; border: 2px solid transparent; }
        .mode-card:hover { transform: translateY(-5px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-color: #007bff; }
        .mode-card h3 { color: #007bff; margin-bottom: 10px; font-size: 1.3em; }
        .mode-card .icon { font-size: 3em; margin-bottom: 15px; }
        label { display: block; margin-top: 15px; font-weight: bold; }
        input, select { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ddd; border-radius: 5px; }
        .btn { padding: 12px 30px; margin: 15px 5px 0 0; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
        .btn-primary { background: #007bff; color: white; }
        .btn-back { background: #6c757d; color: white; }
        .fiche { border: 2px solid #28a745; padding: 20px; margin-top: 20px; border-radius: 8px; background: #f8f9fa; }
        .statut { display: inline-block; padding: 5px 15px; border-radius: 15px; font-weight: bold; }
        .statut-valide { background: #d4edda; color: #155724; }
        .statut-en_attente { background: #fff3cd; color: #856404; }
        .statut-refuse { background: #f8d7da; color: #721c24; }
        .etudiant-checkbox { padding: 10px; background: #f8f9fa; margin: 10px 0; border-radius: 5px; }
        .etudiant-checkbox label { display: inline; margin-left: 10px; font-weight: normal; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #007bff; color: white; }
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

        <h1>📄 Dépôt de rapport de stage</h1>

        <?php if ($erreur): ?>
            <div class="error">❌ <?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>
        
        <?php if ($succes): ?>
            <div class="success">✅ <?= htmlspecialchars($succes) ?></div>
        <?php endif; ?>

        <?php if ($mode === 'choix'): ?>
            <!-- Choix du mode -->
            <div class="info">
                <strong>ℹ️ Information :</strong> Choisissez le mode de dépôt de rapport selon votre besoin.
            </div>

            <div class="mode-selection">
                <div class="mode-card" onclick="window.location='depot_rapport.php?mode=ecole'">
                    <div class="icon">🏫</div>
                    <h3>Par École</h3>
                    <p>Sélectionnez une école puis un dossier pour déposer un rapport commun pour plusieurs étudiants</p>
                </div>

                <div class="mode-card" onclick="window.location='depot_rapport.php?mode=etudiant'">
                    <div class="icon">👨‍🎓</div>
                    <h3>Par Étudiant</h3>
                    <p>Recherchez un étudiant par nom et prénom pour déposer son rapport individuel</p>
                </div>
            </div>

        <?php elseif ($mode === 'ecole' && !isset($_GET['id_ecole']) && !isset($_GET['id_dossier'])): ?>
            <!-- MODE ÉCOLE : Sélection de l'école -->
            <a href="depot_rapport.php" class="btn btn-back">← Retour</a>
            
            <h2 style="margin-top: 20px;">Sélectionnez une école</h2>
            
            <form action="depot_rapport.php" method="GET">
                <input type="hidden" name="mode" value="ecole">
                
                <label for="id_ecole">École :</label>
                <select name="id_ecole" id="id_ecole" required>
                    <option value="">-- Choisir une école --</option>
                    <?php foreach ($ecoles as $ecole): ?>
                        <option value="<?= $ecole['id_ecole'] ?>">
                            <?= htmlspecialchars($ecole['nom_ecole']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" class="btn btn-primary">Continuer →</button>
            </form>

        <?php elseif ($mode === 'ecole' && isset($_GET['id_ecole']) && !isset($_GET['id_dossier'])): ?>
            <!-- MODE ÉCOLE : Sélection du dossier -->
            <a href="depot_rapport.php?mode=ecole" class="btn btn-back">← Retour</a>
            
            <h2 style="margin-top: 20px;">Sélectionnez un dossier validé</h2>
            
            <?php if (empty($dossiers)): ?>
                <div class="warning">⚠️ Aucun dossier validé trouvé pour cette école.</div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Filière</th>
                            <th>Année</th>
                            <th>Période</th>
                            <th>Étudiants</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dossiers as $d): ?>
                            <tr>
                                <td><?= htmlspecialchars($d['filiere']) ?></td>
                                <td><?= htmlspecialchars($d['annee_academique']) ?></td>
                                <td><?= date('d/m/Y', strtotime($d['datedebut'])) ?> - <?= date('d/m/Y', strtotime($d['datefin'])) ?></td>
                                <td><?= $d['nb_etudiants'] ?></td>
                                <td>
                                    <a href="depot_rapport.php?mode=ecole&id_dossier=<?= $d['id_dossier'] ?>" class="btn btn-primary">
                                        Sélectionner
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

        <?php elseif ($mode === 'ecole' && isset($_GET['id_dossier'])): ?>
            <!-- MODE ÉCOLE : Dépôt pour plusieurs étudiants -->
            <a href="depot_rapport.php?mode=ecole&id_ecole=<?= $_GET['id_ecole'] ?? '' ?>" class="btn btn-back">← Retour</a>
            
            <h2 style="margin-top: 20px;">Dépôt de rapport commun</h2>
            
            <?php if (empty($etudiants_dossier)): ?>
                <div class="info">✅ Tous les étudiants de ce dossier ont déjà un rapport.</div>
            <?php else: ?>
                <div class="info">
                    <strong>ℹ️ Information :</strong> Sélectionnez les étudiants qui partagent le même rapport puis déposez le fichier.
                </div>

                <form action="traiter_depot_rapport.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="mode" value="multiple">
                    
                    <h3>Étudiants disponibles :</h3>
                    <?php foreach ($etudiants_dossier as $etu): ?>
                        <div class="etudiant-checkbox">
                            <input type="checkbox" name="etudiants[]" value="<?= $etu['id_etudiant'] ?>" id="etu_<?= $etu['id_etudiant'] ?>">
                            <label for="etu_<?= $etu['id_etudiant'] ?>">
                                <strong><?= htmlspecialchars($etu['nom_etudiant']) ?> <?= htmlspecialchars($etu['prenom_etudiant']) ?></strong>
                                (<?= htmlspecialchars($etu['email_etu']) ?>)
                            </label>
                        </div>
                    <?php endforeach; ?>

                    <label for="rapport" style="margin-top: 20px;">Rapport de stage (PDF) * :</label>
                    <input type="file" name="rapport" id="rapport" accept="application/pdf" required>

                    <button type="submit" class="btn btn-primary">📤 Déposer le rapport</button>
                </form>
            <?php endif; ?>

        <?php elseif ($mode === 'etudiant' && !isset($_GET['nom'])): ?>
            <!-- MODE ÉTUDIANT : Recherche -->
            <a href="depot_rapport.php" class="btn btn-back">← Retour</a>
            
            <h2 style="margin-top: 20px;">Rechercher un étudiant</h2>
            
            <div class="info">
                <strong>ℹ️ Information :</strong> Entrez le nom et le prénom de l'étudiant pour accéder à son dossier.
            </div>

            <form action="depot_rapport.php" method="GET">
                <input type="hidden" name="mode" value="etudiant">
                
                <label for="nom">Nom :</label>
                <input type="text" name="nom" id="nom" required>

                <label for="prenom">Prénom :</label>
                <input type="text" name="prenom" id="prenom" required>

                <button type="submit" class="btn btn-primary">🔍 Rechercher</button>
            </form>

        <?php elseif ($mode === 'etudiant' && $etudiant): ?>
            <!-- MODE ÉTUDIANT : Affichage et dépôt -->
            <a href="depot_rapport.php?mode=etudiant" class="btn btn-back">← Retour</a>
            
            <div class="fiche">
                <h2>Votre dossier de stage</h2>
                
                <p><strong>Nom :</strong> <?= htmlspecialchars($etudiant['nom_etudiant']) ?></p>
                <p><strong>Prénom :</strong> <?= htmlspecialchars($etudiant['prenom_etudiant']) ?></p>
                <p><strong>Email :</strong> <?= htmlspecialchars($etudiant['email_etu']) ?></p>
                
                <hr style="margin: 15px 0;">
                
                <p><strong>École :</strong> <?= htmlspecialchars($etudiant['nom_ecole']) ?></p>
                <p><strong>Filière :</strong> <?= htmlspecialchars($etudiant['filiere']) ?></p>
                <p><strong>Année :</strong> <?= htmlspecialchars($etudiant['annee_academique']) ?></p>
                
                <p>
                    <strong>Statut dossier :</strong> 
                    <span class="statut statut-<?= $etudiant['statut'] ?>">
                        <?php
                        switch($etudiant['statut']) {
                            case 'en_attente': echo '⏳ En attente'; break;
                            case 'valide': echo '✅ Validé'; break;
                            case 'refuse': echo '❌ Refusé'; break;
                        }
                        ?>
                    </span>
                </p>
                
                <hr style="margin: 15px 0;">
                
                <p>
                    <strong>Votre CV :</strong> 
                    <a href="voir_fichier.php?id=<?= $etudiant['id_etudiant'] ?>&type=cv" target="_blank" style="color: #007bff;">
                        📄 Consulter le CV
                    </a>
                </p>
                
                <p>
                    <strong>Rapport de stage :</strong> 
                    <?php if ($etudiant['rapport']): ?>
                        <a href="voir_fichier.php?id=<?= $etudiant['id_etudiant'] ?>&type=rapport" target="_blank" style="color: #007bff;">
                            📑 Consulter le rapport
                        </a>
                    <?php else: ?>
                        <span style="color: #dc3545;">Pas encore déposé</span>
                    <?php endif; ?>
                </p>
            </div>

            <?php if ($etudiant['statut'] === 'refuse'): ?>
                <div class="warning">
                    ❌ <strong>Dossier refusé :</strong> Ce dossier a été refusé. Vous ne pouvez pas déposer de rapport.
                </div>
            <?php elseif ($etudiant['statut'] === 'en_attente'): ?>
                <div class="warning">
                    ⏳ <strong>Dossier en attente :</strong> Votre dossier est en attente de validation. Vous pourrez déposer votre rapport une fois le dossier validé.
                </div>
            <?php elseif (!$etudiant['rapport']): ?>
                <div style="background: #e7f3ff; padding: 20px; margin-top: 20px; border-radius: 8px; border-left: 4px solid #007bff;">
                    <h2 style="color: #007bff; margin-bottom: 15px;">📤 Déposer votre rapport de stage</h2>
                    
                    <form action="traiter_depot_rapport.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="mode" value="individual">
                        <input type="hidden" name="id_etudiant" value="<?= $etudiant['id_etudiant'] ?>">
                        
                        <label for="rapport">Sélectionnez votre rapport (PDF) * :</label>
                        <input type="file" name="rapport" id="rapport" accept="application/pdf" required>
                        
                        <button type="submit" class="btn btn-primary">📤 Envoyer le rapport</button>
                    </form>
                </div>
            <?php else: ?>
                <div class="success" style="margin-top: 20px;">
                    ✅ Votre rapport a déjà été déposé. Merci !
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>
