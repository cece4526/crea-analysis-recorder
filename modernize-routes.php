#!/usr/bin/env php
<?php

/**
 * Script pour moderniser les routes des contrôleurs Symfony
 * Convertit les annotations @Route en attributs PHP 8 #[Route]
 */

$controllerDir = __DIR__ . '/src/Controller';
$files = glob($controllerDir . '/*.php');

foreach ($files as $file) {
    if (basename($file) === 'Api') continue; // Skip API directory
    
    echo "Modernisation de " . basename($file) . "...\n";
    
    $content = file_get_contents($file);
    
    // Skip if already using attributes
    if (strpos($content, '#[Route(') !== false) {
        echo "  → Déjà modernisé\n";
        continue;
    }
    
    // Convert namespace import
    $content = str_replace(
        'use Symfony\Component\Routing\Annotation\Route;',
        'use Symfony\Component\Routing\Attribute\Route;',
        $content
    );
    
    // Convert class-level route annotation
    $content = preg_replace(
        '/\/\*\*\s*\*\s*@Route\("([^"]+)"\)\s*\*\//s',
        '#[Route(\'$1\')]',
        $content
    );
    
    // Convert method-level route annotations - simple pattern
    $content = preg_replace(
        '/\/\*\*\s*\*\s*@Route\("([^"]+)",\s*name="([^"]+)",\s*methods=\{([^}]+)\}\)\s*\*\//s',
        '#[Route(\'$1\', name: \'$2\', methods: [$3])]',
        $content
    );
    
    // Fix quotes in methods array
    $content = preg_replace('/methods: \["([^"]+)"\]/', 'methods: [\'$1\']', $content);
    $content = preg_replace('/methods: \["([^"]+)",\s*"([^"]+)"\]/', 'methods: [\'$1\', \'$2\']', $content);
    
    // Convert simple route annotations
    $content = preg_replace(
        '/\/\*\*\s*\*\s*@Route\("([^"]+)",\s*name="([^"]+)"\)\s*\*\//s',
        '#[Route(\'$1\', name: \'$2\')]',
        $content
    );
    
    // Convert routes with just methods
    $content = preg_replace(
        '/\/\*\*\s*\*\s*@Route\("([^"]+)",\s*methods=\{([^}]+)\}\)\s*\*\//s',
        '#[Route(\'$1\', methods: [$2])]',
        $content
    );
    
    file_put_contents($file, $content);
    echo "  → Modernisé avec succès\n";
}

echo "\nModernisation terminée !\n";
