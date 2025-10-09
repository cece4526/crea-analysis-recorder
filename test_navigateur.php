<?php
// Test simulant exactement les données du navigateur

$data = [
    'of_id' => '1',  // String comme envoyé par le navigateur
    'cuve' => '7',
    'debit_enzyme' => '2.8',
    'temperature_hydrolise' => '68.5',
    'matiere' => '110.0',
    'quantite_enzyme' => '1.8',  // Maintenant avec underscore
    'control_verre' => '1',
    'initial_pilote' => 'WEB',
    'created_at' => date('c')  // Format ISO comme JavaScript
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/cuve-cereales/create');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

echo "Test simulant le navigateur\n";
echo "Données envoyées: " . json_encode($data, JSON_PRETTY_PRINT) . "\n\n";

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

echo "Code HTTP: $httpCode\n";
echo "Réponse: $response\n\n";

if ($httpCode === 200) {
    $result = json_decode($response, true);
    if ($result && $result['success']) {
        echo "✅ TEST NAVIGATEUR RÉUSSI: Formulaire fonctionne correctement!\n";
        echo "ID créé: " . $result['id'] . "\n";
    } else {
        echo "❌ TEST NAVIGATEUR ÉCHOUÉ: " . ($result['message'] ?? 'Erreur inconnue') . "\n";
    }
} else {
    echo "❌ TEST NAVIGATEUR ÉCHOUÉ: Code HTTP $httpCode\n";
}
?>
