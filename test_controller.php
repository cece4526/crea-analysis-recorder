<?php
// Test direct du contrôleur
require_once 'vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Dotenv\Dotenv;

// Charger les variables d'environnement
$dotenv = new Dotenv();
if (file_exists('.env.local')) {
    $dotenv->load('.env.local');
}
if (file_exists('.env')) {
    $dotenv->load('.env');
}

// Définir la variable d'environnement si elle n'existe pas
if (!isset($_ENV['DATABASE_URL'])) {
    $_ENV['DATABASE_URL'] = "mysql://root:@127.0.0.1:3306/crea_analysis_recorder?serverVersion=8.0&charset=utf8mb4";
    putenv('DATABASE_URL=' . $_ENV['DATABASE_URL']);
}

echo "🔧 DATABASE_URL: " . ($_ENV['DATABASE_URL'] ?? 'non définie') . "\n";

try {
    // Créer le kernel Symfony
    $kernel = new App\Kernel('dev', true);
    $kernel->boot();
    
    echo "✅ Kernel Symfony initialisé!\n";
    
    // Créer une requête pour /cereales/dashboard
    $request = Request::create('/cereales/dashboard', 'GET');
    
    // Traiter la requête
    $response = $kernel->handle($request);
    
    echo "📊 Code de réponse: " . $response->getStatusCode() . "\n";
    echo "📄 Contenu (premiers 500 caractères):\n";
    echo substr($response->getContent(), 0, 500) . "\n";
    
    if ($response->getStatusCode() !== 200) {
        echo "❌ Erreur dans la réponse!\n";
        echo $response->getContent();
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "📍 Fichier: " . $e->getFile() . " ligne " . $e->getLine() . "\n";
    echo "📋 Trace:\n" . $e->getTraceAsString() . "\n";
}
