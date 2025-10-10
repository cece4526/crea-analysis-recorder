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
            
            // Récupérer les informations sur les cuves pour chaque OF
            $of1CuveInfo = null;
            $of2CuveInfo = null;
            $of1HeureEnzymeInfo = null;
            $of2HeureEnzymeInfo = null;
            $of1Cuves = [];
            $of2Cuves = [];
            
            if ($of1) {
                $stmt = $pdo->prepare("SELECT cuve, created_at FROM cuve_cereales WHERE of_id = ? ORDER BY created_at DESC LIMIT 1");
                $stmt->execute([$of1['id']]);
                $of1CuveInfo = $stmt->fetch(\PDO::FETCH_ASSOC);
                
                // Récupérer toutes les cuves de cet OF
                $stmt = $pdo->prepare("SELECT DISTINCT cuve FROM cuve_cereales WHERE of_id = ? ORDER BY cuve");
                $stmt->execute([$of1['id']]);
                $of1Cuves = $stmt->fetchAll(\PDO::FETCH_COLUMN);
                
                // Récupérer les informations d'heure enzyme avec la cuve correspondante
                $stmt = $pdo->prepare("
                    SELECT he.heure, he.created_at, he.cuve_cereales_id, cc.cuve as cuve_numero 
                    FROM heure_enzyme he 
                    LEFT JOIN cuve_cereales cc ON he.cuve_cereales_id = cc.id 
                    WHERE he.of_id = ? 
                    ORDER BY he.created_at DESC LIMIT 1
                ");
                $stmt->execute([$of1['id']]);
                $of1HeureEnzymeInfo = $stmt->fetch(\PDO::FETCH_ASSOC);
            }
            
            if ($of2) {
                $stmt = $pdo->prepare("SELECT cuve, created_at FROM cuve_cereales WHERE of_id = ? ORDER BY created_at DESC LIMIT 1");
                $stmt->execute([$of2['id']]);
                $of2CuveInfo = $stmt->fetch(\PDO::FETCH_ASSOC);
                
                // Récupérer toutes les cuves de cet OF
                $stmt = $pdo->prepare("SELECT DISTINCT cuve FROM cuve_cereales WHERE of_id = ? ORDER BY cuve");
                $stmt->execute([$of2['id']]);
                $of2Cuves = $stmt->fetchAll(\PDO::FETCH_COLUMN);
                
                // Récupérer les informations d'heure enzyme avec la cuve correspondante
                $stmt = $pdo->prepare("
                    SELECT he.heure, he.created_at, he.cuve_cereales_id, cc.cuve as cuve_numero 
                    FROM heure_enzyme he 
                    LEFT JOIN cuve_cereales cc ON he.cuve_cereales_id = cc.id 
                    WHERE he.of_id = ? 
                    ORDER BY he.created_at DESC LIMIT 1
                ");
                $stmt->execute([$of2['id']]);
                $of2HeureEnzymeInfo = $stmt->fetch(\PDO::FETCH_ASSOC);
            }
            
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
                'of1_cuve_info' => $of1CuveInfo,
                'of2_cuve_info' => $of2CuveInfo,
                'of1_cuves' => $of1Cuves,
                'of2_cuves' => $of2Cuves,
                'of1_heure_enzyme_info' => $of1HeureEnzymeInfo,
                'of2_heure_enzyme_info' => $of2HeureEnzymeInfo,
                'production_count' => $productionCount,
                'haccp_count' => $haccpCount
            ]);
            
        } catch (\Exception $e) {
            return new Response('Erreur: ' . $e->getMessage() . '<br>Fichier: ' . $e->getFile() . '<br>Ligne: ' . $e->getLine());
        }
    }
}
