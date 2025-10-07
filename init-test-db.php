#!/usr/bin/env php
<?php

/**
 * Script pour initialiser complètement la base de données de test
 */

require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

// Charger les variables d'environnement de test
$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/.env.test');

$testDbName = $_ENV['DATABASE_NAME'];
$user = $_ENV['DATABASE_USER'];
$password = $_ENV['DATABASE_PASSWORD'];
$host = $_ENV['DATABASE_HOST'] ?? 'localhost';

echo "Initialisation de la base de données de test : $testDbName\n";

try {
    // Connexion MySQL pour recréer la base
    $pdo = new PDO("mysql:host=$host", $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    // Supprimer et recréer la base
    $pdo->exec("DROP DATABASE IF EXISTS `$testDbName`");
    $pdo->exec("CREATE DATABASE `$testDbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    
    echo "✓ Base de données '$testDbName' recréée\n";
    
    // Exécuter les commandes Symfony pour créer le schéma
    echo "Création du schéma avec Symfony...\n";
    passthru('php bin/console doctrine:schema:create --env=test');
    
    echo "✓ Schéma créé avec succès !\n";
    
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
    exit(1);
}
