<?php
// Test de connexion à la base de données
try {
    $dsn = "mysql:host=127.0.0.1;dbname=crea_analysis_recorder;charset=utf8mb4";
    $pdo = new PDO($dsn, 'root', '');
    
    echo "✅ Connexion à la base de données réussie!\n";
    
    // Vérifier si la table 'of' existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'of'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Table 'of' trouvée!\n";
        
        // Compter les enregistrements
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM `of`");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "📊 Nombre d'enregistrements OF: " . $result['total'] . "\n";
        
        // Afficher quelques OF
        $stmt = $pdo->query("SELECT * FROM `of` LIMIT 3");
        $ofs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "📋 Premiers OF:\n";
        foreach ($ofs as $of) {
            echo "  - ID: {$of['id']}, Numéro: {$of['numero']}, Statut: {$of['statut']}\n";
        }
    } else {
        echo "❌ Table 'of' non trouvée!\n";
        echo "📋 Tables disponibles:\n";
        $stmt = $pdo->query("SHOW TABLES");
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            echo "  - " . $row[0] . "\n";
        }
    }
    
} catch (PDOException $e) {
    echo "❌ Erreur de connexion: " . $e->getMessage() . "\n";
    
    // Essayer de créer la base de données
    try {
        $pdo = new PDO("mysql:host=127.0.0.1", 'root', '');
        $pdo->exec("CREATE DATABASE IF NOT EXISTS crea_analysis_recorder CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "✅ Base de données créée!\n";
    } catch (PDOException $e2) {
        echo "❌ Impossible de créer la base de données: " . $e2->getMessage() . "\n";
    }
}
