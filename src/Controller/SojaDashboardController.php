<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/soja')]
class SojaDashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'soja_dashboard', methods: ['GET'])]
    public function dashboard(): Response
    {
        try {
            // Connexion directe à la base de données
            $dsn = "mysql:host=127.0.0.1;dbname=crea_analysis_recorder;charset=utf8mb4";
            $pdo = new \PDO($dsn, 'root', '');
            
            // Récupérer les OF pour soja (pour l'instant, tous les OF)
            $stmt = $pdo->prepare("SELECT * FROM ordre_fabrication WHERE statut = 'en_cours' ORDER BY created_at DESC LIMIT 2");
            $stmt->execute();
            $ofsEnCours = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            $of1 = $ofsEnCours[0] ?? null;
            $of2 = $ofsEnCours[1] ?? null;
            
            // Récupérer des statistiques spécifiques au soja
            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM ordre_fabrication");
            $stmt->execute();
            $totalOf = $stmt->fetch(\PDO::FETCH_ASSOC)['total'] ?? 0;
            
            return $this->render('soja/dashboard.html.twig', [
                'of1' => $of1,
                'of2' => $of2,
                'total_of' => $totalOf
            ]);
            
        } catch (\Exception $e) {
            return new Response('Erreur: ' . $e->getMessage() . '<br>Fichier: ' . $e->getFile() . '<br>Ligne: ' . $e->getLine());
        }
    }
}
