<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cereales')]
class CerealesTestController extends AbstractController
{
    #[Route('/test-dashboard', name: 'cereales_test_dashboard', methods: ['GET'])]
    public function testDashboard(): Response
    {
        try {
            // Test 1: Retour simple
            return $this->render('test_simple.html.twig', [
                'message' => 'Test dashboard OK'
            ]);
            
        } catch (\Exception $e) {
            return new Response('Erreur: ' . $e->getMessage() . '<br>Fichier: ' . $e->getFile() . '<br>Ligne: ' . $e->getLine());
        }
    }
    
    #[Route('/test-doctrine', name: 'cereales_test_doctrine', methods: ['GET'])]
    public function testDoctrine(): Response
    {
        try {
            // Utilisation directe de PDO sans Doctrine pour éviter les problèmes
            $dsn = "mysql:host=127.0.0.1;dbname=crea_analysis_recorder;charset=utf8mb4";
            $pdo = new \PDO($dsn, 'root', '');
            
            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM ordre_fabrication");
            $stmt->execute();
            $data = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            return new Response('<h1>Base de données OK</h1><p>Total OF: ' . $data['total'] . '</p>');
            
        } catch (\Exception $e) {
            return new Response('Erreur: ' . $e->getMessage() . '<br>Fichier: ' . $e->getFile() . '<br>Ligne: ' . $e->getLine());
        }
    }
}
