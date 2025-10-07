<?php
/**
 * Script pour initialiser les bases de données de test MySQL avec WAMP64
 */

// Configuration MySQL
$host = '127.0.0.1';
$username = 'root';
$password = '';
$databases = [
    'crea_analysis_recorder',      // Base de développement
    'crea_analysis_recorder_test'  // Base de test
];

try {
    // Connexion à MySQL
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connexion à MySQL réussie\n";
    
    // Créer les bases de données si elles n'existent pas
    foreach ($databases as $dbname) {
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "✅ Base de données '$dbname' créée ou vérifiée\n";
    }
    
    echo "\n🎉 Toutes les bases de données sont prêtes !\n";
    echo "Vous pouvez maintenant exécuter :\n";
    echo "- php bin/console doctrine:migrations:migrate (pour la base de dev)\n";
    echo "- php bin/console doctrine:migrations:migrate --env=test (pour la base de test)\n";
    
} catch (PDOException $e) {
    echo "❌ Erreur de connexion à MySQL : " . $e->getMessage() . "\n";
    echo "Vérifiez que WAMP64 est démarré et que MySQL fonctionne.\n";
    exit(1);
}
