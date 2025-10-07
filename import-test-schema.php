<?php

$testDbName = 'crea_analysis_recorder_test';
$user = 'root';
$password = '';
$host = '127.0.0.1';

echo "Importation du schéma dans la base de test : $testDbName\n";

try {
    // Lire le fichier SQL
    $sql = file_get_contents(__DIR__ . '/database_schema.sql');
    
    // Remplacer le nom de la base par celui de test
    $sql = str_replace('crea_analysis_recorder', $testDbName, $sql);
    
    // Connexion MySQL
    $pdo = new PDO("mysql:host=$host", $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    // Exécuter le script SQL modifié
    $pdo->exec($sql);
    
    echo "✓ Schéma importé avec succès !\n";
    
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
    exit(1);
}
