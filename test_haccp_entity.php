<?php
// Test pour identifier le problème avec HACCP

require_once 'vendor/autoload.php';

use App\Entity\HACCP;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

// Configuration de la base de données
$dbParams = [
    'driver'   => 'pdo_mysql',
    'host'     => '127.0.0.1',
    'dbname'   => 'crea_analysis_recorder',
    'user'     => 'root',
    'password' => '',
    'charset'  => 'utf8mb4',
];

$config = ORMSetup::createAttributeMetadataConfiguration([__DIR__.'/src'], true);
$connection = DriverManager::getConnection($dbParams, $config);
$entityManager = new EntityManager($connection, $config);

echo "=== TEST CRÉATION HACCP ===\n";

try {
    $haccp = new HACCP();
    $haccp->setFiltrePasteurisateurResultat(true);
    $haccp->setFiltreNepResultat(true);
    $haccp->setTemperatureCible(120);
    $haccp->setTemperatureIndique(122);
    $haccp->setInitialProduction('TEST');
    $haccp->setInitialNEP('NEP');
    $haccp->setInitialTEMP('TMP');
    $haccp->setOfId(1);
    
    // Juste pour tester, on ne fait pas le persist/flush
    echo "Entité HACCP créée avec succès\n";
    echo "InitialProduction: " . $haccp->getInitialProduction() . "\n";
    
    // Testons avec un persist/flush
    $entityManager->persist($haccp);
    $entityManager->flush();
    
    echo "✅ HACCP enregistré avec succès! ID: " . $haccp->getId() . "\n";
    
} catch (\Exception $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
?>
