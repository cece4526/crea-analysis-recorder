<?php
/**
 * Script pour créer la vue vue_suivi_production dans MySQL
 */

// Configuration de la base de données
$host = 'localhost';
$dbname = 'crea_analysis_recorder';
$username = 'root';
$password = ''; // Mot de passe vide par défaut sur WAMP

try {
    // Connexion à MySQL
    echo "🔌 Connexion à MySQL...\n";
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion réussie!\n";

    // Créer la base de données si elle n'existe pas
    echo "🗃️ Création de la base de données si nécessaire...\n";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✅ Base de données prête!\n";

    // Sélectionner la base de données
    $pdo->exec("USE `$dbname`");

    // Vérifier si les tables existent
    echo "🔍 Vérification des tables...\n";
    $result = $pdo->query("SHOW TABLES LIKE 'production'");
    if ($result->rowCount() == 0) {
        echo "⚠️ Table 'production' non trouvée. Création des tables de base...\n";
        
        // Créer les tables de base si elles n'existent pas
        $createTables = "
        CREATE TABLE IF NOT EXISTS `production` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(255) DEFAULT NULL,
            `quantity` INT DEFAULT NULL,
            `status` VARCHAR(255) DEFAULT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

        CREATE TABLE IF NOT EXISTS `of` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `numero` VARCHAR(50) UNIQUE NOT NULL,
            `date_creation` DATE DEFAULT NULL,
            `date_prevue` DATE DEFAULT NULL,
            `date_realisation` DATE DEFAULT NULL,
            `statut` VARCHAR(100) DEFAULT 'En attente',
            `quantite_prevue` INT DEFAULT NULL,
            `quantite_realisee` INT DEFAULT NULL,
            `commentaires` TEXT DEFAULT NULL,
            `production_id` INT DEFAULT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            
            FOREIGN KEY (`production_id`) REFERENCES `production`(`id`) ON DELETE SET NULL,
            INDEX `idx_of_production` (`production_id`),
            INDEX `idx_of_numero` (`numero`),
            INDEX `idx_of_statut` (`statut`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        
        $pdo->exec($createTables);
        echo "✅ Tables de base créées!\n";

        // Insérer des données de test
        echo "📊 Insertion de données de test...\n";
        $testData = "
        INSERT INTO `production` (`name`, `quantity`, `status`) VALUES
        ('Production Lait de Soja Bio', 1000, 'En cours'),
        ('Production Lait de Riz Complet', 750, 'Terminée'),
        ('Production Lait d''Avoine', 500, 'En attente'),
        ('Production Mélange Céréales', 1200, 'En cours');

        INSERT INTO `of` (`numero`, `date_creation`, `date_prevue`, `statut`, `quantite_prevue`, `quantite_realisee`, `production_id`) VALUES
        ('OF-2025-001', '2025-10-01', '2025-10-15', 'En cours', 500, 300, 1),
        ('OF-2025-002', '2025-10-01', '2025-10-15', 'En cours', 500, 450, 1),
        ('OF-2025-003', '2025-10-02', '2025-10-10', 'Terminé', 750, 750, 2),
        ('OF-2025-004', '2025-10-03', '2025-10-20', 'En attente', 500, 0, 3),
        ('OF-2025-005', '2025-10-04', '2025-10-18', 'En cours', 600, 400, 4),
        ('OF-2025-006', '2025-10-04', '2025-10-18', 'En cours', 600, 500, 4);
        ";
        
        $pdo->exec($testData);
        echo "✅ Données de test insérées!\n";
    } else {
        echo "✅ Tables existantes trouvées!\n";
    }

    // Supprimer la vue si elle existe déjà
    echo "🗑️ Suppression de l'ancienne vue si elle existe...\n";
    $pdo->exec("DROP VIEW IF EXISTS `vue_suivi_production`");

    // Créer la vue de suivi de production
    echo "🏗️ Création de la vue vue_suivi_production...\n";
    $createView = "
    CREATE VIEW `vue_suivi_production` AS
    SELECT 
        p.id as production_id,
        p.name as production_name,
        p.status as production_status,
        COUNT(o.id) as nombre_of,
        SUM(o.quantite_prevue) as quantite_totale_prevue,
        SUM(o.quantite_realisee) as quantite_totale_realisee,
        AVG(CASE WHEN o.quantite_prevue > 0 THEN (o.quantite_realisee / o.quantite_prevue) * 100 END) as taux_realisation
    FROM `production` p
    LEFT JOIN `of` o ON p.id = o.production_id
    GROUP BY p.id, p.name, p.status
    ";

    $pdo->exec($createView);
    echo "✅ Vue vue_suivi_production créée avec succès!\n";

    // Test de la vue
    echo "🧪 Test de la vue...\n";
    $result = $pdo->query("SELECT * FROM vue_suivi_production ORDER BY production_name");
    $data = $result->fetchAll(PDO::FETCH_ASSOC);
    
    echo "📋 Résultats de la vue:\n";
    echo str_pad("Production", 30) . str_pad("Statut", 15) . str_pad("OF", 5) . str_pad("Prévue", 10) . str_pad("Réalisée", 10) . "Taux\n";
    echo str_repeat("-", 80) . "\n";
    
    foreach ($data as $row) {
        $taux = $row['taux_realisation'] ? round($row['taux_realisation'], 1) . '%' : 'N/A';
        echo str_pad(substr($row['production_name'], 0, 28), 30) . 
             str_pad($row['production_status'], 15) . 
             str_pad($row['nombre_of'], 5) . 
             str_pad($row['quantite_totale_prevue'] ?? '0', 10) . 
             str_pad($row['quantite_totale_realisee'] ?? '0', 10) . 
             $taux . "\n";
    }
    
    echo "\n✅ Vue testée avec succès! " . count($data) . " lignes trouvées.\n";

    // Quelques requêtes utiles
    echo "\n📊 Quelques statistiques utiles:\n";
    
    // Statistiques globales
    $stats = $pdo->query("
        SELECT 
            COUNT(*) as total_productions,
            SUM(nombre_of) as total_of,
            SUM(quantite_totale_prevue) as total_quantite_prevue,
            SUM(quantite_totale_realisee) as total_quantite_realisee,
            ROUND(AVG(taux_realisation), 2) as taux_realisation_moyen
        FROM vue_suivi_production
    ")->fetch(PDO::FETCH_ASSOC);
    
    echo "• Total productions: " . $stats['total_productions'] . "\n";
    echo "• Total OF: " . $stats['total_of'] . "\n";
    echo "• Quantité totale prévue: " . $stats['total_quantite_prevue'] . "\n";
    echo "• Quantité totale réalisée: " . $stats['total_quantite_realisee'] . "\n";
    echo "• Taux de réalisation moyen: " . $stats['taux_realisation_moyen'] . "%\n";

    echo "\n🎉 Script terminé avec succès!\n";
    echo "👉 Vous pouvez maintenant utiliser la vue 'vue_suivi_production' dans vos requêtes SQL.\n";

} catch (PDOException $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    exit(1);
}
?>
