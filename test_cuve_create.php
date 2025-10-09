<?php
// Test simple pour la route cuve-cereales/create

echo "Test de l'endpoint /cuve-cereales/create\n";

$data = [
    'of_id' => 1,
    'cuve' => 5,
    'debit_enzyme' => '2.5',
    'temperature_hydrolise' => '65.0',
    'matiere' => '100.0',
    'quantiteEnzyme' => '1.5',
    'control_verre' => 1,
    'initial_pilote' => 'TEST'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/cuve-cereales/create');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen(json_encode($data))
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Code HTTP: $httpCode\n";
echo "Réponse: $response\n";
?>
