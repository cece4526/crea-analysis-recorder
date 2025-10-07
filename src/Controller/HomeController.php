<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        try {
            // Récupérer quelques statistiques pour la page d'accueil
            $dsn = "mysql:host=127.0.0.1;dbname=crea_analysis_recorder;charset=utf8mb4";
            $pdo = new \PDO($dsn, 'root', '');
            
            // Statistiques générales
            $stats = [];
            
            // Compter les OF
            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM `of`");
            $stmt->execute();
            $stats['total_of'] = $stmt->fetch(\PDO::FETCH_ASSOC)['total'] ?? 0;
            
            // Compter les OF en cours
            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM `of` WHERE statut = 'en_cours'");
            $stmt->execute();
            $stats['of_en_cours'] = $stmt->fetch(\PDO::FETCH_ASSOC)['total'] ?? 0;
            
            // Compter les productions
            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM production");
            $stmt->execute();
            $stats['total_productions'] = $stmt->fetch(\PDO::FETCH_ASSOC)['total'] ?? 0;
            
            // Compter les contrôles HACCP
            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM haccp");
            $stmt->execute();
            $stats['total_haccp'] = $stmt->fetch(\PDO::FETCH_ASSOC)['total'] ?? 0;
            
            return $this->render('home/index.html.twig', [
                'stats' => $stats
            ]);
            
        } catch (\Exception $e) {
            // Si erreur de DB, afficher la page d'accueil sans stats
            return $this->render('home/index.html.twig', [
                'stats' => [
                    'total_of' => 0,
                    'of_en_cours' => 0,
                    'total_productions' => 0,
                    'total_haccp' => 0
                ],
                'db_error' => true
            ]);
        }
    }
    
    #[Route('/dashboard', name: 'dashboard_redirect')]
    public function dashboardRedirect(): Response
    {
        // Rediriger vers le dashboard des céréales qui fonctionne
        return $this->redirectToRoute('cereales_simple_dashboard');
    }
}
