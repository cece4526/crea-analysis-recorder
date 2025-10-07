<?php

$host = '127.0.0.1';
$user = 'root';
$password = '';
$database = 'crea_analysis_recorder_test';

echo "Nettoyage complet de la base de test...\n";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Désactiver les contraintes
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
    
    // Récupérer toutes les tables
    $result = $pdo->query('SHOW TABLES');
    $tables = $result->fetchAll(PDO::FETCH_COLUMN);
    
    // Supprimer toutes les tables
    foreach ($tables as $table) {
        echo "Suppression de la table $table\n";
        $pdo->exec("DROP TABLE `$table`");
    }
    
    // Réactiver les contraintes
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
    
    echo "✓ Base nettoyée avec succès !\n";
    
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
    exit(1);
}
