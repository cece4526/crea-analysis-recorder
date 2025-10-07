<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TestController extends AbstractController
{
    #[Route('/test', name: 'test')]
    public function test(): Response
    {
        return new Response('<h1>Test OK</h1><p>Le serveur fonctionne !</p>');
    }
    
    #[Route('/test-assets', name: 'test_assets')]
    public function testAssets(): Response
    {
        return $this->render('test_assets.html.twig');
    }
    
    #[Route('/test-colors', name: 'test_colors')]
    public function testColors(): Response
    {
        return new Response('
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Test Couleurs</title>
            <link href="/build/app.css" rel="stylesheet">
            <link href="/build/home.css" rel="stylesheet">
            <style>
                .test-box { padding: 20px; margin: 10px; color: white; }
            </style>
        </head>
        <body>
            <h1>Test des couleurs INOVé</h1>
            <div class="test-box bg-primary-custom">Vert primaire #9BC53D</div>
            <div class="test-box bg-dark-custom">Vert foncé #2D5016</div>
            <div class="test-box bg-medium-custom">Vert moyen #4A7C59</div>
            <div class="test-box" style="background-color: #F39C12;">Orange accent #F39C12</div>
            <div class="btn btn-primary-custom">Bouton test</div>
        </body>
        </html>
        ');
    }
    
    #[Route('/test-db', name: 'test_db')]
    public function testDb(): Response
    {
        try {
            $dsn = "mysql:host=127.0.0.1;dbname=crea_analysis_recorder;charset=utf8mb4";
            $pdo = new \PDO($dsn, 'root', '');
            
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM `of`");
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            return new Response('<h1>Base de données OK</h1><p>Nombre d\'OF: ' . $result['total'] . '</p>');
            
        } catch (\Exception $e) {
            return new Response('<h1>Erreur DB</h1><p>' . $e->getMessage() . '</p>');
        }
    }
}
