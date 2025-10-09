<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cereales')]
class CerealesSimpleController extends AbstractController
{
    #[Route('/simple-dashboard', name: 'cereales_simple_dashboard', methods: ['GET'])]
    public function simpleDashboard(): Response
    {
        try {
            // Connexion directe à la base de données
            $dsn = "mysql:host=127.0.0.1;dbname=crea_analysis_recorder;charset=utf8mb4";
            $pdo = new \PDO($dsn, 'root', '');
            
            // Récupérer les OF en cours
            $stmt = $pdo->prepare("SELECT * FROM ordre_fabrication WHERE statut = 'en_cours' ORDER BY created_at DESC LIMIT 2");
            $stmt->execute();
            $ofsEnCours = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            $of1 = $ofsEnCours[0] ?? null;
            $of2 = $ofsEnCours[1] ?? null;
            
            // Récupérer quelques données de production
            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM production");
            $stmt->execute();
            $productionCount = $stmt->fetch(\PDO::FETCH_ASSOC)['total'] ?? 0;
            
            // Récupérer des données HACCP
            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM haccp");
            $stmt->execute();
            $haccpCount = $stmt->fetch(\PDO::FETCH_ASSOC)['total'] ?? 0;
            
            return $this->render('cereales/simple_dashboard.html.twig', [
                'of1' => $of1,
                'of2' => $of2,
                'production_count' => $productionCount,
                'haccp_count' => $haccpCount
            ]);
            
        } catch (\Exception $e) {
            return new Response('Erreur: ' . $e->getMessage() . '<br>Fichier: ' . $e->getFile() . '<br>Ligne: ' . $e->getLine());
        }
    }
}
