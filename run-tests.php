#!/usr/bin/env php
<?php

/**
 * Script de lancement des tests unitaires complets
 * CRÉA-ANALYSIS RECORDER
 */

echo "========================================\n";
echo "TESTS UNITAIRES CRÉA-ANALYSIS RECORDER\n";
echo "========================================\n\n";

$baseDir = __DIR__;
$vendorDir = $baseDir . '/vendor';

// Vérifier que PHPUnit est installé
if (!file_exists($vendorDir . '/bin/phpunit')) {
    echo "❌ PHPUnit non trouvé. Veuillez exécuter 'composer install'.\n";
    exit(1);
}

echo "📋 Structure des tests :\n";
echo "  • Tests d'entités (OF, Production, HeureEnzyme)\n";
echo "  • Tests de contrôleurs (Routes modernisées)\n";
echo "  • Tests de repositories\n";
echo "  • Tests de formulaires\n";
echo "  • Tests d'application globale\n\n";

// Tests des entités
echo "🧪 Tests des entités...\n";
echo "--------------------\n";

$entityTests = [
    'Entity/OFTest.php',
    'Entity/HeureEnzymeSimpleTest.php',
    'Entity/ProductionTest.php'
];

foreach ($entityTests as $test) {
    echo "  → $test\n";
}

// Tests des contrôleurs
echo "\n🎮 Tests des contrôleurs...\n";
echo "-------------------------\n";

$controllerTests = [
    'Controller/OFControllerTest.php',
    'Controller/ProductionControllerTest.php',
    'Controller/HeureEnzymeControllerTest.php'
];

foreach ($controllerTests as $test) {
    echo "  → $test\n";
}

// Tests des repositories
echo "\n📚 Tests des repositories...\n";
echo "--------------------------\n";

$repositoryTests = [
    'Repository/OFRepositoryTest.php'
];

foreach ($repositoryTests as $test) {
    echo "  → $test\n";
}

// Tests des formulaires
echo "\n📝 Tests des formulaires...\n";
echo "-------------------------\n";

$formTests = [
    'Form/OFTypeTest.php'
];

foreach ($formTests as $test) {
    echo "  → $test\n";
}

// Test d'application globale
echo "\n🚀 Test d'application...\n";
echo "----------------------\n";
echo "  → ApplicationTest.php\n";

echo "\n========================================\n";
echo "Pour lancer tous les tests :\n";
echo "php vendor/bin/phpunit tests/\n";
echo "\nPour lancer un test spécifique :\n";
echo "php vendor/bin/phpunit tests/Entity/OFTest.php\n";
echo "\nPour lancer les tests par catégorie :\n";
echo "php vendor/bin/phpunit tests/Entity/\n";
echo "php vendor/bin/phpunit tests/Controller/\n";
echo "========================================\n";

// Vérification de la configuration
echo "\n🔧 Vérification de la configuration...\n";

if (file_exists($baseDir . '/phpunit.xml')) {
    echo "✅ Configuration PHPUnit trouvée\n";
} else {
    echo "⚠️  Fichier phpunit.xml non trouvé\n";
}

if (file_exists($baseDir . '/.env.test')) {
    echo "✅ Configuration de test trouvée\n";
} else {
    echo "⚠️  Fichier .env.test non trouvé\n";
}

echo "\n🎯 Tests prêts à être exécutés !\n";
echo "================================\n";
