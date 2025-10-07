<?php

namespace App\Controller;

use App\Repository\OFRepository;
use App\Repository\ProductionRepository;
use App\Repository\HACCPRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cereales')]
class CerealesDashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'cereales_dashboard', methods: ['GET'])]
    public function dashboard(
        OFRepository $ofRepository,
        ProductionRepository $productionRepository,
        HACCPRepository $haccpRepository
    ): Response {
        // Récupérer les 2 derniers OF en cours pour les céréales
        $ofsEnCours = $ofRepository->findBy(
            ['statut' => 'en_cours'],
            ['createdAt' => 'DESC'],
            2
        );
        
        // Préparer les données pour les OF
        $of1 = $ofsEnCours[0] ?? null;
        $of2 = $ofsEnCours[1] ?? null;
        
        // Calculer les statistiques globales
        $today = new \DateTime('today');
        $productionsActives = $productionRepository->count(['statut' => 'en_cours']);
        $productionsEnCours = $productionRepository->count(['statut' => 'en_cours']);
        $productionsTerminees = $productionRepository->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->where('p.statut = :statut')
            ->andWhere('p.date >= :today')
            ->setParameter('statut', 'termine')
            ->setParameter('today', $today)
            ->getQuery()
            ->getSingleScalarResult();
            
        $alertesHaccp = $haccpRepository->count(['conforme' => false]);
        
        // Simuler les statuts des processus pour la démo
        $of1Data = null;
        $of2Data = null;
        
        if ($of1) {
            $of1Data = [
                'numero' => $of1->getNumero(),
                'date' => $of1->getDate(),
                'preparation_statut' => 'en-cours',
                'decantation_statut' => 'attente',
                'enzyme_statut' => 'attente',
                'haccp_statut' => 'attente',
                'okara_statut' => 'attente'
            ];
        }
        
        if ($of2) {
            $of2Data = [
                'numero' => $of2->getNumero(),
                'date' => $of2->getDate(),
                'preparation_statut' => 'termine',
                'decantation_statut' => 'en-cours',
                'enzyme_statut' => 'attente',
                'haccp_statut' => 'attente',
                'okara_statut' => 'attente'
            ];
        }
        
        return $this->render('cereales/dashboard.html.twig', [
            'of1' => $of1Data,
            'of2' => $of2Data,
            'productions_actives' => $productionsActives,
            'productions_en_cours' => $productionsEnCours,
            'productions_terminees' => $productionsTerminees,
            'alertes_haccp' => $alertesHaccp,
            'current_time' => new \DateTime(),
        ]);
    }
    
    #[Route('/api/status-update', name: 'cereales_status_update', methods: ['POST'])]
    public function updateStatus(): Response
    {
        // API endpoint pour mettre à jour les statuts en temps réel
        // Simuler des données de statut mises à jour
        $statusData = [
            'of1' => [
                'preparation_statut' => random_int(0, 1) ? 'termine' : 'en-cours',
                'decantation_statut' => random_int(0, 1) ? 'en-cours' : 'attente',
                'enzyme_statut' => random_int(0, 1) ? 'en-cours' : 'attente',
                'haccp_statut' => random_int(0, 1) ? 'en-cours' : 'attente',
                'okara_statut' => random_int(0, 1) ? 'en-cours' : 'attente'
            ],
            'of2' => [
                'preparation_statut' => 'termine',
                'decantation_statut' => random_int(0, 1) ? 'termine' : 'en-cours',
                'enzyme_statut' => random_int(0, 1) ? 'en-cours' : 'attente',
                'haccp_statut' => random_int(0, 1) ? 'en-cours' : 'attente',
                'okara_statut' => random_int(0, 1) ? 'en-cours' : 'attente'
            ],
            'timestamp' => (new \DateTime())->format('Y-m-d H:i:s')
        ];
        
        return $this->json($statusData);
    }
    
    #[Route('/process/{type}/{ofId}', name: 'cereales_process_detail', methods: ['GET'])]
    public function processDetail(string $type, int $ofId): Response
    {
        // Détails d'un processus spécifique (préparation, décantation, etc.)
        return $this->render('cereales/process_detail.html.twig', [
            'process_type' => $type,
            'of_id' => $ofId,
            'process_data' => $this->getProcessData($type, $ofId)
        ]);
    }
    
    private function getProcessData(string $type, int $ofId): array
    {
        // Simuler des données de processus détaillées
        $baseData = [
            'of_id' => $ofId,
            'started_at' => new \DateTime('-2 hours'),
            'estimated_completion' => new \DateTime('+1 hour'),
            'operator' => 'Opérateur ' . chr(65 + random_int(0, 25)),
            'parameters' => []
        ];
        
        switch ($type) {
            case 'preparation':
                $baseData['parameters'] = [
                    'temperature' => random_int(18, 25) . '°C',
                    'humidite' => random_int(40, 60) . '%',
                    'duree_prevue' => '2h30'
                ];
                break;
                
            case 'decantation':
                $baseData['parameters'] = [
                    'vitesse_rotation' => random_int(100, 200) . ' rpm',
                    'temperature' => random_int(20, 30) . '°C',
                    'duree_cycle' => '45 min'
                ];
                break;
                
            case 'enzyme':
                $baseData['parameters'] = [
                    'type_enzyme' => 'Amylase',
                    'concentration' => random_int(5, 15) . ' mg/L',
                    'ph_optimal' => '6.5',
                    'temperature_reaction' => random_int(35, 45) . '°C'
                ];
                break;
                
            case 'haccp':
                $baseData['parameters'] = [
                    'point_critique' => 'Température pasteurisation',
                    'limite_critique' => '72°C min',
                    'mesure_actuelle' => random_int(70, 75) . '°C',
                    'conforme' => random_int(0, 1) ? 'Oui' : 'Non'
                ];
                break;
                
            case 'okara':
                $baseData['parameters'] = [
                    'quantite_produite' => random_int(50, 150) . ' kg',
                    'humidite_finale' => random_int(75, 85) . '%',
                    'destination' => 'Alimentation animale'
                ];
                break;
        }
        
        return $baseData;
    }
}
