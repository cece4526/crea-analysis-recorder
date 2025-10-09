<?php
// Test direct de la logique HACCP pour debug
require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Kernel;
use Symfony\Component\HttpFoundation\Request;

$kernel = new Kernel($_SERVER['APP_ENV'] ?? 'dev', (bool) ($_SERVER['APP_DEBUG'] ?? true));
$request = Request::createFromGlobals();
$kernel->boot();
$container = $kernel->getContainer();

$entityManager = $container->get('doctrine.orm.entity_manager');
$haccpRepository = $entityManager->getRepository('App\Entity\HACCP');

echo "<h1>Test Debug HACCP Messages</h1>";

// Test pour OF ID 1 (devrait avoir un enregistrement existant)
$ofId1 = 1;
$existingHaccp1 = $haccpRepository->findOneBy(['of_id' => $ofId1]);

echo "<h2>Test OF ID: $ofId1</h2>";
echo "Existing HACCP found: " . ($existingHaccp1 ? 'OUI' : 'NON') . "<br>";

if ($existingHaccp1) {
    echo "ID de l'enregistrement: " . $existingHaccp1->getId() . "<br>";
    echo "Message attendu: 'Les données HACCP ont été <strong>mises à jour</strong> avec succès.'<br>";
} else {
    echo "Message attendu: 'Les données HACCP ont été <strong>enregistrées</strong> avec succès.'<br>";
}

// Test pour OF ID 999 (ne devrait pas avoir d'enregistrement)
$ofId999 = 999;
$existingHaccp999 = $haccpRepository->findOneBy(['of_id' => $ofId999]);

echo "<h2>Test OF ID: $ofId999</h2>";
echo "Existing HACCP found: " . ($existingHaccp999 ? 'OUI' : 'NON') . "<br>";

if ($existingHaccp999) {
    echo "ID de l'enregistrement: " . $existingHaccp999->getId() . "<br>";
    echo "Message attendu: 'Les données HACCP ont été <strong>mises à jour</strong> avec succès.'<br>";
} else {
    echo "Message attendu: 'Les données HACCP ont été <strong>enregistrées</strong> avec succès.'<br>";
}

echo "<hr>";
echo "<p><strong>Instructions:</strong></p>";
echo "<p>1. Testons maintenant avec l'OF ID 1 qui a déjà un enregistrement</p>";
echo "<p>2. Le message devrait dire 'mis à jour' et non 'enregistrés'</p>";
echo "<p>3. Vérifiez les logs du serveur pour voir les messages de debug</p>";
?>
