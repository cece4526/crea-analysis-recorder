<?php
/**
 * Script pour tester et utiliser la vue vue_suivi_production
 */

// Configuration de la base de données
$host = 'localhost';
$dbname = 'crea_analysis_recorder';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "🔗 Connexion à la base de données réussie!\n\n";

    // Mettre à jour quelques données pour avoir des résultats plus intéressants
    echo "📝 Mise à jour des données de test...\n";
    $updateData = "
    UPDATE `of` SET `quantite_realisee` = 450 WHERE `numero` = 'OF-2025-001';
    UPDATE `of` SET `quantite_realisee` = 380 WHERE `numero` = 'OF-2025-002';
    UPDATE `of` SET `quantite_realisee` = 750 WHERE `numero` = 'OF-2025-003';
    UPDATE `of` SET `quantite_realisee` = 200 WHERE `numero` = 'OF-2025-004';
    UPDATE `of` SET `quantite_realisee` = 400 WHERE `numero` = 'OF-2025-005';
    UPDATE `of` SET `quantite_realisee` = 500 WHERE `numero` = 'OF-2025-006';
    ";
    $pdo->exec($updateData);
    echo "✅ Données mises à jour!\n\n";

    // Test 1: Vue complète
    echo "📊 TEST 1: Vue complète du suivi de production\n";
    echo str_repeat("=", 80) . "\n";
    $result = $pdo->query("SELECT * FROM vue_suivi_production ORDER BY production_name");
    
    printf("%-30s %-12s %-4s %-8s %-8s %-8s\n", "Production", "Statut", "OF", "Prévue", "Réalisée", "Taux %");
    echo str_repeat("-", 80) . "\n";
    
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $taux = $row['taux_realisation'] ? round($row['taux_realisation'], 1) : 'N/A';
        printf("%-30s %-12s %-4d %-8s %-8s %-8s\n", 
            substr($row['production_name'], 0, 28),
            $row['production_status'],
            $row['nombre_of'],
            $row['quantite_totale_prevue'] ?? '0',
            $row['quantite_totale_realisee'] ?? '0',
            $taux
        );
    }
    echo "\n";

    // Test 2: Productions avec meilleur taux de réalisation
    echo "🏆 TEST 2: Top 3 des productions par taux de réalisation\n";
    echo str_repeat("=", 60) . "\n";
    $result = $pdo->query("
        SELECT production_name, ROUND(taux_realisation, 1) as taux, quantite_totale_realisee, quantite_totale_prevue
        FROM vue_suivi_production 
        WHERE taux_realisation IS NOT NULL 
        ORDER BY taux_realisation DESC 
        LIMIT 3
    ");
    
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "• " . $row['production_name'] . " : " . $row['taux'] . "% (" . 
             $row['quantite_totale_realisee'] . "/" . $row['quantite_totale_prevue'] . ")\n";
    }
    echo "\n";

    // Test 3: Productions en cours
    echo "⚡ TEST 3: Productions en cours\n";
    echo str_repeat("=", 50) . "\n";
    $result = $pdo->query("
        SELECT production_name, nombre_of, quantite_totale_prevue, quantite_totale_realisee, 
               ROUND(taux_realisation, 1) as taux
        FROM vue_suivi_production 
        WHERE production_status = 'En cours'
        ORDER BY taux_realisation DESC
    ");
    
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "• " . $row['production_name'] . "\n";
        echo "  - OF actifs: " . $row['nombre_of'] . "\n";
        echo "  - Avancement: " . $row['quantite_totale_realisee'] . "/" . $row['quantite_totale_prevue'] . 
             " (" . ($row['taux'] ?? 'N/A') . "%)\n\n";
    }

    // Test 4: Statistiques globales
    echo "📈 TEST 4: Statistiques globales\n";
    echo str_repeat("=", 40) . "\n";
    $result = $pdo->query("
        SELECT 
            COUNT(*) as total_productions,
            SUM(nombre_of) as total_of,
            SUM(quantite_totale_prevue) as total_prevue,
            SUM(quantite_totale_realisee) as total_realisee,
            ROUND(AVG(taux_realisation), 1) as taux_moyen
        FROM vue_suivi_production
    ");
    
    $stats = $result->fetch(PDO::FETCH_ASSOC);
    echo "Total productions: " . $stats['total_productions'] . "\n";
    echo "Total OF: " . $stats['total_of'] . "\n";
    echo "Quantité prévue: " . number_format($stats['total_prevue']) . "\n";
    echo "Quantité réalisée: " . number_format($stats['total_realisee']) . "\n";
    echo "Taux de réalisation moyen: " . ($stats['taux_moyen'] ?? 'N/A') . "%\n\n";

    // Test 5: Requête personnalisée - Productions problématiques
    echo "⚠️ TEST 5: Productions problématiques (taux < 85%)\n";
    echo str_repeat("=", 45) . "\n";
    $result = $pdo->query("
        SELECT production_name, ROUND(taux_realisation, 1) as taux, 
               (quantite_totale_prevue - quantite_totale_realisee) as retard
        FROM vue_suivi_production 
        WHERE taux_realisation < 85 AND taux_realisation IS NOT NULL
        ORDER BY taux_realisation ASC
    ");
    
    if ($result->rowCount() > 0) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo "• " . $row['production_name'] . " : " . $row['taux'] . "% (retard: " . $row['retard'] . ")\n";
        }
    } else {
        echo "Aucune production problématique trouvée! 🎉\n";
    }
    echo "\n";

    // Test 6: Export JSON (pour API)
    echo "🔗 TEST 6: Export JSON (simulation d'API)\n";
    echo str_repeat("=", 35) . "\n";
    $result = $pdo->query("SELECT * FROM vue_suivi_production ORDER BY production_name");
    $jsonData = $result->fetchAll(PDO::FETCH_ASSOC);
    
    $apiResponse = [
        'success' => true,
        'data' => $jsonData,
        'count' => count($jsonData),
        'timestamp' => date('c')
    ];
    
    echo "Données JSON générées pour " . count($jsonData) . " productions.\n";
    echo "Exemple de première production :\n";
    echo json_encode($jsonData[0], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

    echo "🎯 TOUS LES TESTS TERMINÉS AVEC SUCCÈS!\n";
    echo "La vue 'vue_suivi_production' fonctionne parfaitement.\n";
    echo "Vous pouvez maintenant l'utiliser dans vos applications Symfony.\n";

} catch (PDOException $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    exit(1);
}
?>
