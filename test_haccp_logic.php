<?php
// Test pour vérifier la logique de modification HACCP

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

echo "=== TEST LOGIQUE MODIFICATION HACCP ===\n";

// Test 1: Vérifier s'il existe un enregistrement pour OF ID 1
$existingHaccp = $entityManager->getRepository(HACCP::class)->findOneBy(['of_id' => 1]);

if ($existingHaccp) {
    echo "✅ Enregistrement HACCP trouvé pour OF ID 1 : ID " . $existingHaccp->getId() . "\n";
    echo "   InitialProduction: " . $existingHaccp->getInitialProduction() . "\n";
    echo "   Température cible: " . $existingHaccp->getTemperatureCible() . "\n";
    
    $isUpdate = true;
    $action = $isUpdate ? 'mis à jour' : 'enregistrés';
    echo "   Action attendue: '$action'\n";
} else {
    echo "❌ Aucun enregistrement HACCP trouvé pour OF ID 1\n";
}

// Test 2: Vérifier pour OF ID 2
$existingHaccp2 = $entityManager->getRepository(HACCP::class)->findOneBy(['of_id' => 2]);

if ($existingHaccp2) {
    echo "✅ Enregistrement HACCP trouvé pour OF ID 2 : ID " . $existingHaccp2->getId() . "\n";
} else {
    echo "❌ Aucun enregistrement HACCP trouvé pour OF ID 2\n";
    echo "   Action attendue pour création: 'enregistrés'\n";
}

echo "\n=== RÉSULTATS ===\n";
echo "Si vous modifiez l'OF 1, vous devriez voir 'mis à jour'\n";
echo "Si vous créez pour l'OF 2, vous devriez voir 'enregistrés'\n";
?>
