<?php

namespace App\Controller\Api;

use App\Entity\Production;
use App\Entity\OF;
use App\Repository\ProductionRepository;
use App\Repository\OFRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * API Controller pour le workflow de production industrielle
 * Gestion du processus : Production → OF → Analyses → Corrections → Finalisation → PDF
 */
#[Route('/api/workflow', name: 'api_workflow_')]
class WorkflowController extends AbstractController
{
    public function __construct(
        private ProductionRepository $productionRepository,
        private OFRepository $ofRepository
    ) {
    }

    /**
     * ÉTAPE 1: Liste des productions disponibles pour sélection
     * GET /api/workflow/productions
     */
    #[Route('/productions', name: 'productions', methods: ['GET'])]
    public function getProductions(): JsonResponse
    {
        $productions = $this->productionRepository->findAllActive();
        
        $data = [];
        foreach ($productions as $production) {
            $data[] = [
                'id' => $production->getId(),
                'date' => $production->getDate()?->format('Y-m-d H:i:s'),
                'type' => $production->getType(), // 'soja' ou 'cereales'
                'statut' => $production->getStatut(),
                'quantite_totale' => $production->getQuantiteTotale(),
                'description' => $production->getDescription(),
                'ofs_count' => $this->ofRepository->countByProduction($production)
            ];
        }

        return $this->json([
            'success' => true,
            'message' => 'Productions récupérées',
            'data' => $data,
            'workflow_step' => 1,
            'next_step' => 'Sélectionner une production puis choisir un OF'
        ]);
    }

    /**
     * ÉTAPE 2: Liste des OF pour une production sélectionnée
     * GET /api/workflow/productions/{productionId}/ofs
     */
    #[Route('/productions/{productionId}/ofs', name: 'production_ofs', methods: ['GET'])]
    public function getProductionOFs(int $productionId): JsonResponse
    {
        $production = $this->productionRepository->find($productionId);
        
        if (!$production) {
            return $this->json([
                'success' => false,
                'message' => 'Production non trouvée'
            ], Response::HTTP_NOT_FOUND);
        }

        $ofs = $this->ofRepository->findByProduction($production);
        
        $data = [];
        foreach ($ofs as $of) {
            $data[] = [
                'id' => $of->getId(),
                'numero' => $of->getNumero(),
                'nature' => $of->getNature(),
                'statut' => $of->getStatut(),
                'date_creation' => $of->getDateCreation()?->format('Y-m-d H:i:s'),
                'type_production' => $production->getType(),
                'analyses_count' => $this->getAnalysesCount($of, $production->getType()),
                'workflow_complete' => $this->isWorkflowComplete($of, $production->getType())
            ];
        }

        return $this->json([
            'success' => true,
            'message' => 'OF récupérés pour la production',
            'data' => $data,
            'production' => [
                'id' => $production->getId(),
                'type' => $production->getType(),
                'statut' => $production->getStatut()
            ],
            'workflow_step' => 2,
            'next_step' => 'Sélectionner un OF pour commencer les analyses'
        ]);
    }

    /**
     * ÉTAPE 3: Initialiser le workflow pour un OF (SOJA ou CÉRÉALES)
     * POST /api/workflow/of/{ofId}/init
     */
    #[Route('/of/{ofId}/init', name: 'init_of_workflow', methods: ['POST'])]
    public function initOFWorkflow(int $ofId): JsonResponse
    {
        $of = $this->ofRepository->find($ofId);
        
        if (!$of) {
            return $this->json([
                'success' => false,
                'message' => 'OF non trouvé'
            ], Response::HTTP_NOT_FOUND);
        }

        $production = $of->getProduction();
        $type = $production->getType();

        // Déterminer les étapes du workflow selon le type
        $workflow = $this->getWorkflowSteps($type);
        
        // Vérifier l'état actuel du workflow
        $currentStep = $this->getCurrentWorkflowStep($of, $type);

        return $this->json([
            'success' => true,
            'message' => "Workflow $type initialisé",
            'data' => [
                'of_id' => $of->getId(),
                'of_numero' => $of->getNumero(),
                'production_type' => $type,
                'workflow_steps' => $workflow,
                'current_step' => $currentStep,
                'next_action' => $this->getNextAction($of, $type, $currentStep)
            ],
            'workflow_step' => 3
        ]);
    }

    /**
     * ÉTAPE 4: Obtenir le statut complet du workflow d'un OF
     * GET /api/workflow/of/{ofId}/status
     */
    #[Route('/of/{ofId}/status', name: 'of_workflow_status', methods: ['GET'])]
    public function getOFWorkflowStatus(int $ofId): JsonResponse
    {
        $of = $this->ofRepository->find($ofId);
        
        if (!$of) {
            return $this->json([
                'success' => false,
                'message' => 'OF non trouvé'
            ], Response::HTTP_NOT_FOUND);
        }

        $production = $of->getProduction();
        $type = $production->getType();
        
        $status = $this->getDetailedWorkflowStatus($of, $type);

        return $this->json([
            'success' => true,
            'data' => $status,
            'workflow_step' => 4
        ]);
    }

    /**
     * Méthodes utilitaires privées
     */
    private function getWorkflowSteps(string $type): array
    {
        if ($type === 'soja') {
            return [
                1 => ['name' => 'HACCP', 'required' => true, 'entity' => 'HACCP'],
                2 => ['name' => 'Analyses Soja (1h30 max)', 'required' => true, 'entity' => 'AnalyseSoja'],
                3 => ['name' => 'Avant Correction', 'required' => true, 'entity' => 'AvCorrectSoja'],
                4 => ['name' => 'Après Correction', 'required' => true, 'entity' => 'ApCorrectSoja'],
                5 => ['name' => 'Okara', 'required' => true, 'entity' => 'Okara'],
                6 => ['name' => 'Finalisation', 'required' => true, 'entity' => null],
                7 => ['name' => 'PDF Rapport', 'required' => false, 'entity' => null]
            ];
        } else { // cereales
            return [
                1 => ['name' => 'Cuves Céréales', 'required' => true, 'entity' => 'CuveCereales'],
                2 => ['name' => 'Décanteur', 'required' => true, 'entity' => 'DecanteurCereales'],
                3 => ['name' => 'Analyses Régulières', 'required' => true, 'entity' => 'Echantillons'],
                4 => ['name' => 'Avant Correction', 'required' => true, 'entity' => 'AvCorrectCereales'],
                5 => ['name' => 'Après Correction', 'required' => true, 'entity' => 'ApCorrectCereales'],
                6 => ['name' => 'Finalisation', 'required' => true, 'entity' => null],
                7 => ['name' => 'PDF Rapport', 'required' => false, 'entity' => null]
            ];
        }
    }

    private function getCurrentWorkflowStep(OF $of, string $type): int
    {
        // Logique pour déterminer l'étape actuelle selon les données présentes
        // À implémenter selon les relations avec les entités
        return 1;
    }

    private function getNextAction(OF $of, string $type, int $currentStep): string
    {
        $workflows = $this->getWorkflowSteps($type);
        
        if (isset($workflows[$currentStep])) {
            return "Compléter: " . $workflows[$currentStep]['name'];
        }
        
        return "Workflow terminé";
    }

    private function getAnalysesCount(OF $of, string $type): int
    {
        // Compter les analyses selon le type
        // À implémenter avec les repositories appropriés
        return 0;
    }

    private function isWorkflowComplete(OF $of, string $type): bool
    {
        // Vérifier si le workflow est complet
        // À implémenter selon les relations
        return false;
    }

    private function getDetailedWorkflowStatus(OF $of, string $type): array
    {
        // Retourner un statut détaillé du workflow
        // À implémenter selon les besoins
        return [
            'of_id' => $of->getId(),
            'type' => $type,
            'completion_percentage' => 0
        ];
    }
}
