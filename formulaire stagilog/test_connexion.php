<?php
// Test rapide de connexion et vérification des tables

require 'config.php';

echo "<h1>Test de connexion</h1>";

try {
    // Tester la connexion
    $pdo->query("SELECT 1");
    echo "<p style='color:green;'>✅ Connexion à la base de données : OK</p>";
    
    // Lister les tables
    echo "<h2>Tables existantes :</h2>";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li>$table";
        
        // Compter les lignes
        try {
            $count = $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
            echo " ($count lignes)";
        } catch (Exception $e) {
            echo " (erreur)";
        }
        
        echo "</li>";
    }
    echo "</ul>";
    
    // Vérifier spécifiquement la table ecoles
    echo "<h2>Vérification table 'ecoles' :</h2>";
    try {
        $stmt = $pdo->query("SELECT id_ecole, nom_ecole FROM ecoles LIMIT 5");
        $ecoles = $stmt->fetchAll();
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Nom</th></tr>";
        foreach ($ecoles as $ecole) {
            echo "<tr><td>{$ecole['id_ecole']}</td><td>{$ecole['nom_ecole']}</td></tr>";
        }
        echo "</table>";
    } catch (PDOException $e) {
        echo "<p style='color:red;'>❌ Erreur : " . $e->getMessage() . "</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color:red;'>❌ Erreur de connexion : " . $e->getMessage() . "</p>";
}
?>
