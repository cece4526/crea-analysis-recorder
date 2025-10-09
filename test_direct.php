<?php
// Test simple de l'endpoint
require 'vendor/autoload.php';

use App\Kernel;
use Symfony\Component\HttpFoundation\Request;

$kernel = new Kernel('dev', true);
$kernel->boot();

// Créer une requête POST simulée
$data = [
    'of_id' => 1,
    'cuve' => 5,
    'debit_enzyme' => '2.5',
    'temperature_hydrolise' => '65.0',
    'matiere' => '100.0',
    'quantite_enzyme' => '1.5',
    'control_verre' => 1,
    'initial_pilote' => 'TEST'
];

$request = Request::create(
    '/cuve-cereales/create',
    'POST',
    [],
    [],
    [],
    ['CONTENT_TYPE' => 'application/json'],
    json_encode($data)
);

try {
    $response = $kernel->handle($request);
    echo "Code HTTP: " . $response->getStatusCode() . "\n";
    echo "Réponse: " . $response->getContent() . "\n";
    
    if ($response->getStatusCode() === 200) {
        echo "✅ TEST RÉUSSI!\n";
    } else {
        echo "❌ TEST ÉCHOUÉ\n";
    }
} catch (\Exception $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

$kernel->shutdown();
