<?php

namespace App\Controller\Api;

use App\Entity\OF;
use App\Repository\OFRepository;
use App\Service\PDFGeneratorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur pour la génération de rapports PDF
 * Génère les analyses complètes sous forme de tableaux PDF
 */
#[Route('/api/pdf', name: 'api_pdf_')]
class PDFController extends AbstractController
{
    public function __construct(
        private OFRepository $ofRepository,
        private PDFGeneratorService $pdfGenerator
    ) {
    }

    /**
     * Génère le PDF complet d'un OF (SOJA ou CÉRÉALES)
     * GET /api/pdf/of/{ofId}/generate
     */
    #[Route('/of/{ofId}/generate', name: 'generate_of_report', methods: ['GET'])]
    public function generateOFReport(int $ofId): Response
    {
        $of = $this->ofRepository->find($ofId);
        
        if (!$of) {
            return $this->json([
                'success' => false,
                'message' => 'OF non trouvé'
            ], Response::HTTP_NOT_FOUND);
        }

        $production = $of->getProduction();
        
        // Vérifier que l'OF est finalisé
        if ($of->getStatut() !== 'finalise') {
            return $this->json([
                'success' => false,
                'message' => 'OF doit être finalisé pour générer le PDF',
                'statut_actuel' => $of->getStatut()
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            // Générer le PDF selon le type de production
            if ($production->getType() === 'soja') {
                $pdfContent = $this->pdfGenerator->generateSojaReport($of);
                $filename = "Rapport_Soja_OF_{$of->getNumero()}_{$production->getDate()->format('Y-m-d')}.pdf";
            } else {
                $pdfContent = $this->pdfGenerator->generateCerealesReport($of);
                $filename = "Rapport_Cereales_OF_{$of->getNumero()}_{$production->getDate()->format('Y-m-d')}.pdf";
            }

            // Retourner le PDF
            return new Response(
                $pdfContent,
                Response::HTTP_OK,
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => "attachment; filename=\"$filename\"",
                    'Content-Length' => strlen($pdfContent)
                ]
            );

        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Erreur lors de la génération du PDF',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Prévisualise les données qui seront dans le PDF
     * GET /api/pdf/of/{ofId}/preview
     */
    #[Route('/of/{ofId}/preview', name: 'preview_of_report', methods: ['GET'])]
    public function previewOFReport(int $ofId): JsonResponse
    {
        $of = $this->ofRepository->find($ofId);
        
        if (!$of) {
            return $this->json([
                'success' => false,
                'message' => 'OF non trouvé'
            ], Response::HTTP_NOT_FOUND);
        }

        $production = $of->getProduction();
        
        try {
            // Récupérer toutes les données selon le type
            if ($production->getType() === 'soja') {
                $reportData = $this->pdfGenerator->getSojaReportData($of);
            } else {
                $reportData = $this->pdfGenerator->getCerealesReportData($of);
            }

            return $this->json([
                'success' => true,
                'message' => 'Données du rapport récupérées',
                'data' => [
                    'of' => [
                        'id' => $of->getId(),
                        'numero' => $of->getNumero(),
                        'nature' => $of->getNature(),
                        'statut' => $of->getStatut()
                    ],
                    'production' => [
                        'id' => $production->getId(),
                        'type' => $production->getType(),
                        'date' => $production->getDate()->format('Y-m-d H:i:s'),
                        'quantite_totale' => $production->getQuantiteTotale()
                    ],
                    'report_data' => $reportData,
                    'pdf_filename' => "Rapport_{$production->getType()}_OF_{$of->getNumero()}_{$production->getDate()->format('Y-m-d')}.pdf"
                ]
            ]);

        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des données',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Liste tous les PDF générés pour une production
     * GET /api/pdf/production/{productionId}/reports
     */
    #[Route('/production/{productionId}/reports', name: 'production_reports', methods: ['GET'])]
    public function getProductionReports(int $productionId): JsonResponse
    {
        $ofs = $this->ofRepository->findByProductionId($productionId);
        
        if (empty($ofs)) {
            return $this->json([
                'success' => false,
                'message' => 'Aucun OF trouvé pour cette production'
            ], Response::HTTP_NOT_FOUND);
        }

        $reports = [];
        foreach ($ofs as $of) {
            if ($of->getStatut() === 'finalise') {
                $production = $of->getProduction();
                $reports[] = [
                    'of_id' => $of->getId(),
                    'of_numero' => $of->getNumero(),
                    'production_type' => $production->getType(),
                    'date_production' => $production->getDate()->format('Y-m-d'),
                    'pdf_url' => $this->generateUrl('api_pdf_generate_of_report', ['ofId' => $of->getId()]),
                    'preview_url' => $this->generateUrl('api_pdf_preview_of_report', ['ofId' => $of->getId()]),
                    'filename' => "Rapport_{$production->getType()}_OF_{$of->getNumero()}_{$production->getDate()->format('Y-m-d')}.pdf"
                ];
            }
        }

        return $this->json([
            'success' => true,
            'message' => 'Rapports PDF disponibles',
            'data' => $reports,
            'total' => count($reports)
        ]);
    }

    /**
     * Génère un rapport global de production (tous les OF)
     * GET /api/pdf/production/{productionId}/global-report
     */
    #[Route('/production/{productionId}/global-report', name: 'global_production_report', methods: ['GET'])]
    public function generateGlobalProductionReport(int $productionId): Response
    {
        $ofs = $this->ofRepository->findByProductionId($productionId);
        
        if (empty($ofs)) {
            return $this->json([
                'success' => false,
                'message' => 'Aucun OF trouvé pour cette production'
            ], Response::HTTP_NOT_FOUND);
        }

        $production = $ofs[0]->getProduction();
        
        // Vérifier que tous les OF sont finalisés
        $nonFinalizedOFs = array_filter($ofs, fn($of) => $of->getStatut() !== 'finalise');
        if (!empty($nonFinalizedOFs)) {
            return $this->json([
                'success' => false,
                'message' => 'Tous les OF doivent être finalisés',
                'non_finalized_count' => count($nonFinalizedOFs)
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $pdfContent = $this->pdfGenerator->generateGlobalProductionReport($production, $ofs);
            $filename = "Rapport_Global_{$production->getType()}_Production_{$production->getId()}_{$production->getDate()->format('Y-m-d')}.pdf";

            return new Response(
                $pdfContent,
                Response::HTTP_OK,
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => "attachment; filename=\"$filename\"",
                    'Content-Length' => strlen($pdfContent)
                ]
            );

        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Erreur lors de la génération du rapport global',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
