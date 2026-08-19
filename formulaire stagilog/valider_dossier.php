<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: administration.php');
    exit;
}

$id_dossier = filter_input(INPUT_POST, 'id_dossier', FILTER_VALIDATE_INT);
$action = $_POST['action'] ?? '';

if (!$id_dossier || !in_array($action, ['valider', 'refuser'])) {
    $_SESSION['erreur'] = "Paramètres invalides.";
    header('Location: administration.php');
    exit;
}

try {
    $pdo->beginTransaction();
    
    // Vérifier que le dossier existe et est en attente
    $stmt = $pdo->prepare("SELECT statut FROM dossiers WHERE id_dossier = :id");
    $stmt->execute(['id' => $id_dossier]);
    $dossier = $stmt->fetch();
    
    if (!$dossier) {
        throw new Exception("Dossier introuvable.");
    }
    
    if ($dossier['statut'] !== 'en_attente') {
        throw new Exception("Ce dossier a déjà été traité.");
    }
    
    if ($action === 'valider') {
        // Validation : simplement mettre à jour le statut
        $stmt = $pdo->prepare("UPDATE dossiers SET statut = 'valide' WHERE id_dossier = :id");
        $stmt->execute(['id' => $id_dossier]);
        
        $message = "Dossier #$id_dossier validé avec succès !";
        
    } else {
        // Refus : supprimer tous les étudiants liés puis supprimer le dossier
        // Étape 1 : Compter les étudiants
        $stmt = $pdo->prepare("SELECT COUNT(*) as nb FROM etudiants WHERE id_dossier = :id");
        $stmt->execute(['id' => $id_dossier]);
        $nb_etudiants = $stmt->fetch()['nb'];
        
        // Étape 2 : Supprimer les étudiants
        $stmt = $pdo->prepare("DELETE FROM etudiants WHERE id_dossier = :id");
        $stmt->execute(['id' => $id_dossier]);
        
        // Étape 3 : Supprimer le dossier
        $stmt = $pdo->prepare("DELETE FROM dossiers WHERE id_dossier = :id");
        $stmt->execute(['id' => $id_dossier]);
        
        $message = "Dossier #$id_dossier refusé et supprimé avec succès (ainsi que $nb_etudiants étudiant(s) associé(s)).";
    }
    
    $pdo->commit();
    $_SESSION['succes'] = $message;
    
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['erreur'] = "Erreur : " . $e->getMessage();
}

header('Location: administration.php');
exit;
?>
