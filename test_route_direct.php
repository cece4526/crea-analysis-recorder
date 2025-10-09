<?php
// Test direct de la route pour vérifier qu'elle est accessible

$url = 'http://localhost:8000/cuve-cereales/create';
$data = [
    'of_id' => 1,
    'cuve' => 8,
    'debit_enzyme' => '3.5',
    'temperature_hydrolise' => '75.0',
    'matiere' => '130.0',
    'quantite_enzyme' => '2.5',
    'control_verre' => 1,
    'initial_pilote' => 'TEST_ROUTE'
];

echo "=== TEST DIRECT DE LA ROUTE ===\n";
echo "URL: $url\n";
echo "Données: " . json_encode($data, JSON_PRETTY_PRINT) . "\n\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_VERBOSE, true);
curl_setopt($ch, CURLOPT_STDERR, fopen('php://stdout', 'w'));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_error($ch)) {
    echo "❌ ERREUR CURL: " . curl_error($ch) . "\n";
} else {
    echo "\n=== RÉPONSE ===\n";
    echo "Code HTTP: $httpCode\n";
    echo "Réponse: $response\n";
    
    if ($httpCode === 200) {
        echo "✅ Route accessible et fonctionnelle!\n";
    } else {
        echo "❌ Problème avec la route (code $httpCode)\n";
    }
}

curl_close($ch);
?>
