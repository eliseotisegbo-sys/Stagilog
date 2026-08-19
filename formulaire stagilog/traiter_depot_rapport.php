<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: depot_rapport.php');
    exit;
}

$mode = $_POST['mode'] ?? '';

if (!in_array($mode, ['individual', 'multiple'])) {
    $_SESSION['erreur'] = "Mode invalide.";
    header('Location: depot_rapport.php');
    exit;
}

// Vérifier le fichier rapport
if (!isset($_FILES['rapport']) || $_FILES['rapport']['error'] !== UPLOAD_ERR_OK) {
    $_SESSION['erreur'] = "Le fichier n'a pas été correctement transmis.";
    header('Location: depot_rapport.php');
    exit;
}

$extension = strtolower(pathinfo($_FILES['rapport']['name'], PATHINFO_EXTENSION));
if ($extension !== 'pdf') {
    $_SESSION['erreur'] = "Seuls les fichiers PDF sont acceptés.";
    header('Location: depot_rapport.php');
    exit;
}

// Créer le dossier d'upload
$dossierUpload = __DIR__ . '/uploads/rapports/';
if (!is_dir($dossierUpload)) {
    mkdir($dossierUpload, 0755, true);
}

try {
    $pdo->beginTransaction();
    
    if ($mode === 'individual') {
        // Mode individuel : un étudiant
        $id_etudiant = filter_input(INPUT_POST, 'id_etudiant', FILTER_VALIDATE_INT);
        
        if (!$id_etudiant) {
            throw new Exception("Identifiant étudiant invalide.");
        }
        
        // Vérifier l'étudiant et le statut du dossier
        $stmt = $pdo->prepare("
            SELECT e.nom_etudiant, e.prenom_etudiant, e.rapport, d.statut
            FROM etudiants e
            INNER JOIN dossiers d ON d.id_dossier = e.id_dossier
            WHERE e.id_etudiant = :id
        ");
        $stmt->execute(['id' => $id_etudiant]);
        $etudiant = $stmt->fetch();
        
        if (!$etudiant) {
            throw new Exception("Étudiant introuvable.");
        }
        
        if ($etudiant['statut'] === 'en_attente') {
            throw new Exception("Le dossier est en attente de validation. Vous ne pouvez pas déposer de rapport pour le moment.");
        }
        
        if ($etudiant['statut'] === 'refuse') {
            throw new Exception("Le dossier a été refusé. Vous ne pouvez pas déposer de rapport.");
        }
        
        if ($etudiant['rapport']) {
            throw new Exception("Un rapport a déjà été déposé pour cet étudiant.");
        }
        
        // Upload et enregistrement
        $nomFichier = 'rapport_' . $id_etudiant . '_' . time() . '.pdf';
        
        if (!move_uploaded_file($_FILES['rapport']['tmp_name'], $dossierUpload . $nomFichier)) {
            throw new Exception("Erreur lors de l'enregistrement du fichier.");
        }
        
        $stmt = $pdo->prepare("UPDATE etudiants SET rapport = :rapport WHERE id_etudiant = :id");
        $stmt->execute(['rapport' => $nomFichier, 'id' => $id_etudiant]);
        
        $pdo->commit();
        
        $_SESSION['succes'] = "Rapport déposé avec succès pour " . htmlspecialchars($etudiant['prenom_etudiant'] . ' ' . $etudiant['nom_etudiant']) . " !";
        header('Location: depot_rapport.php?mode=etudiant&nom=' . urlencode($etudiant['nom_etudiant']) . '&prenom=' . urlencode($etudiant['prenom_etudiant']));
        exit;
        
    } else {
        // Mode multiple : plusieurs étudiants
        $etudiants_ids = $_POST['etudiants'] ?? [];
        
        if (empty($etudiants_ids)) {
            throw new Exception("Veuillez sélectionner au moins un étudiant.");
        }
        
        // Upload unique du fichier
        $nomFichier = 'rapport_commun_' . time() . '.pdf';
        
        if (!move_uploaded_file($_FILES['rapport']['tmp_name'], $dossierUpload . $nomFichier)) {
            throw new Exception("Erreur lors de l'enregistrement du fichier.");
        }
        
        $nb_updated = 0;
        
        foreach ($etudiants_ids as $id) {
            $id = filter_var($id, FILTER_VALIDATE_INT);
            
            if (!$id) continue;
            
            // Vérifier que l'étudiant n'a pas déjà de rapport et que le dossier est validé
            $stmt = $pdo->prepare("
                SELECT e.rapport, d.statut
                FROM etudiants e
                INNER JOIN dossiers d ON d.id_dossier = e.id_dossier
                WHERE e.id_etudiant = :id
            ");
            $stmt->execute(['id' => $id]);
            $check = $stmt->fetch();
            
            if ($check && !$check['rapport'] && $check['statut'] === 'valide') {
                $stmt = $pdo->prepare("UPDATE etudiants SET rapport = :rapport WHERE id_etudiant = :id");
                $stmt->execute(['rapport' => $nomFichier, 'id' => $id]);
                $nb_updated++;
            }
        }
        
        $pdo->commit();
        
        if ($nb_updated > 0) {
            $_SESSION['succes'] = "Rapport déposé avec succès pour $nb_updated étudiant(s) !";
        } else {
            $_SESSION['erreur'] = "Aucun étudiant n'a pu recevoir le rapport (déjà déposé ou dossier non validé).";
        }
        
        header('Location: depot_rapport.php');
        exit;
    }
    
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['erreur'] = "Erreur : " . $e->getMessage();
    header('Location: depot_rapport.php');
    exit;
}
?>
