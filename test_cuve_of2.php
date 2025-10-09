<?php
// Test de soumission cuve céréales pour OF #2

$data = [
    'of_id' => 2,
    'cuve' => 6,
    'debit_enzyme' => '3.0',
    'temperature_hydrolise' => '70.0',
    'matiere' => '120.0',
    'quantite_enzyme' => '2.0',
    'control_verre' => 1,
    'initial_pilote' => 'TEST_OF2'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/cuve-cereales/create');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

echo "Envoi des données: " . json_encode($data, JSON_PRETTY_PRINT) . "\n\n";

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

echo "Code HTTP: $httpCode\n";
echo "Réponse: $response\n\n";

if ($httpCode === 200) {
    $result = json_decode($response, true);
    if ($result && $result['success']) {
        echo "✅ TEST RÉUSSI: Formulaire cuve céréales fonctionne correctement!\n";
        echo "ID créé: " . $result['id'] . "\n";
    } else {
        echo "❌ TEST ÉCHOUÉ: " . ($result['message'] ?? 'Erreur inconnue') . "\n";
    }
} else {
    echo "❌ TEST ÉCHOUÉ: Code HTTP $httpCode\n";
}
?>
