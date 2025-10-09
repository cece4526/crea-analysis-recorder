<?php
// Test de soumission cuve céréales

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
echo "Réponse: $response\n";

if ($httpCode == 200) {
    $result = json_decode($response, true);
    if ($result && isset($result['success']) && $result['success']) {
        echo "\n✅ TEST RÉUSSI: Formulaire cuve céréales fonctionne correctement!\n";
        echo "ID créé: " . ($result['id'] ?? 'N/A') . "\n";
    } else {
        echo "\n❌ TEST ÉCHOUÉ: " . ($result['message'] ?? 'Erreur inconnue') . "\n";
    }
} else {
    echo "\n❌ TEST ÉCHOUÉ: Code HTTP $httpCode\n";
}
?>
