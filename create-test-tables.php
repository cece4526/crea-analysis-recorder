<?php

$host = '127.0.0.1';
$user = 'root';
$password = '';

echo "Création des tables de test...\n";

try {
    $pdo = new PDO("mysql:host=$host", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = file_get_contents(__DIR__ . '/test-schema.sql');
    $pdo->exec($sql);
    
    echo "✓ Tables créées avec succès !\n";
    
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
    exit(1);
}
