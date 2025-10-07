<?php

namespace App\Controller\Api;

use App\Entity\{OF, HACCP, AnalyseSoja, AvCorrectSoja, ApCorrectSoja, Okara};
use App\Repository\{OFRepository, HACCPRepository, AnalyseSojaRepository, 
    AvCorrectSojaRepository, ApCorrectSojaRepository, OkaraRepository};
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur API pour le workflow de production SOJA
 * Workflow: HACCP → AnalyseSoja (1h30) → AvCorrectSoja → ApCorrectSoja → Okara → Finalisation → PDF
 */
#[Route('/api/soja', name: 'api_soja_')]
class SojaWorkflowController extends AbstractController
{
    public function __construct(
        private OFRepository $ofRepository,
        private HACCPRepository $haccpRepository,
        private AnalyseSojaRepository $analyseSojaRepository,
        private AvCorrectSojaRepository $avCorrectSojaRepository,
        private ApCorrectSojaRepository $apCorrectSojaRepository,
        private OkaraRepository $okaraRepository
    ) {
    }

    /**
     * ÉTAPE 1 SOJA: Saisie des données HACCP
     * POST /api/soja/of/{ofId}/haccp
     */
    #[Route('/of/{ofId}/haccp', name: 'haccp_create', methods: ['POST'])]
    public function createHACCP(int $ofId, Request $request): JsonResponse
    {
        $of = $this->ofRepository->find($ofId);
        
        if (!$of || $of->getProduction()->getType() !== 'soja') {
            return $this->json([
                'success' => false,
                'message' => 'OF Soja non trouvé'
            ], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        $haccp = new HACCP();
        $haccp->setOf($of);
        $haccp->setTemperatureCible($data['temperature_cible'] ?? null);
        $haccp->setTemperatureIndique($data['temperature_indique'] ?? null);
        $haccp->setPasteurisationOk($data['pasteurisation_ok'] ?? false);
        $haccp->setNepResultat($data['nep_resultat'] ?? false);
        $haccp->setDateControle(new \DateTime());

        $this->haccpRepository->save($haccp, true);

        return $this->json([
            'success' => true,
            'message' => 'Données HACCP enregistrées',
            'data' => [
                'haccp_id' => $haccp->getId(),
                'of_id' => $of->getId(),
                'temperature_cible' => $haccp->getTemperatureCible(),
                'pasteurisation_ok' => $haccp->isPasteurisationOk()
            ],
            'next_step' => 'Commencer les analyses Soja (toutes les 1h30 max)'
        ]);
    }

    /**
     * ÉTAPE 2 SOJA: Saisie des analyses (toutes les 1h30 max)
     * POST /api/soja/of/{ofId}/analyse
     */
    #[Route('/of/{ofId}/analyse', name: 'analyse_create', methods: ['POST'])]
    public function createAnalyseSoja(int $ofId, Request $request): JsonResponse
    {
        $of = $this->ofRepository->find($ofId);
        
        if (!$of || $of->getProduction()->getType() !== 'soja') {
            return $this->json([
                'success' => false,
                'message' => 'OF Soja non trouvé'
            ], Response::HTTP_NOT_FOUND);
        }

        // Vérifier qu'il y a bien eu un HACCP avant
        $haccp = $this->haccpRepository->findOneBy(['of' => $of]);
        if (!$haccp) {
            return $this->json([
                'success' => false,
                'message' => 'HACCP requis avant les analyses'
            ], Response::HTTP_BAD_REQUEST);
        }

        // Vérifier l'intervalle de 1h30 max avec la dernière analyse
        $lastAnalyse = $this->analyseSojaRepository->findLastByOF($of);
        if ($lastAnalyse) {
            $interval = $lastAnalyse->getHeureControle()->diff(new \DateTime());
            $minutes = ($interval->h * 60) + $interval->i;
            
            if ($minutes < 90) {
                return $this->json([
                    'success' => false,
                    'message' => "Dernière analyse il y a {$minutes}min. Attendre 1h30 max entre analyses",
                    'next_analyse_at' => $lastAnalyse->getHeureControle()->add(new \DateInterval('PT90M'))->format('H:i')
                ], Response::HTTP_TOO_EARLY);
            }
        }

        $data = json_decode($request->getContent(), true);

        $analyse = new AnalyseSoja();
        $analyse->setOf($of);
        $analyse->setHeureControle(new \DateTime());
        $analyse->setTemperature($data['temperature'] ?? null);
        $analyse->setProteine($data['proteine'] ?? null);
        $analyse->setExtraitSec($data['extrait_sec'] ?? null);
        $analyse->setPh($data['ph'] ?? null);
        $analyse->setControleur($data['controleur'] ?? '');

        $this->analyseSojaRepository->save($analyse, true);

        // Compter le nombre d'analyses pour cet OF
        $totalAnalyses = $this->analyseSojaRepository->countByOF($of);

        return $this->json([
            'success' => true,
            'message' => 'Analyse Soja enregistrée',
            'data' => [
                'analyse_id' => $analyse->getId(),
                'heure_controle' => $analyse->getHeureControle()->format('H:i:s'),
                'temperature' => $analyse->getTemperature(),
                'proteine' => $analyse->getProteine(),
                'total_analyses' => $totalAnalyses
            ],
            'next_step' => 'Continuer analyses (max 1h30) ou passer aux corrections'
        ]);
    }

    /**
     * GET Toutes les analyses d'un OF
     * GET /api/soja/of/{ofId}/analyses
     */
    #[Route('/of/{ofId}/analyses', name: 'analyses_list', methods: ['GET'])]
    public function getAnalysesSoja(int $ofId): JsonResponse
    {
        $of = $this->ofRepository->find($ofId);
        
        if (!$of) {
            return $this->json(['success' => false, 'message' => 'OF non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $analyses = $this->analyseSojaRepository->findByOFOrderedByTime($of);
        
        $data = [];
        foreach ($analyses as $analyse) {
            $data[] = [
                'id' => $analyse->getId(),
                'heure_controle' => $analyse->getHeureControle()->format('H:i:s'),
                'temperature' => $analyse->getTemperature(),
                'proteine' => $analyse->getProteine(),
                'extrait_sec' => $analyse->getExtraitSec(),
                'ph' => $analyse->getPh(),
                'controleur' => $analyse->getControleur()
            ];
        }

        return $this->json([
            'success' => true,
            'data' => $data,
            'total' => count($data),
            'production_en_cours' => $this->isProductionEnCours($of)
        ]);
    }

    /**
     * ÉTAPE 3 SOJA: Avant Correction
     * POST /api/soja/of/{ofId}/avant-correction
     */
    #[Route('/of/{ofId}/avant-correction', name: 'avant_correction', methods: ['POST'])]
    public function createAvantCorrection(int $ofId, Request $request): JsonResponse
    {
        $of = $this->ofRepository->find($ofId);
        
        if (!$of || $of->getProduction()->getType() !== 'soja') {
            return $this->json(['success' => false, 'message' => 'OF Soja non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        $avCorrect = new AvCorrectSoja();
        $avCorrect->setOf($of);
        $avCorrect->setDate(new \DateTime());
        $avCorrect->setTank($data['tank'] ?? null);
        $avCorrect->setEauAjouter($data['eau_ajouter'] ?? null);
        $avCorrect->setMatiere($data['matiere'] ?? null);
        $avCorrect->setProduitFini($data['produit_fini'] ?? null);
        $avCorrect->setEsTank($data['es_tank'] ?? null);
        $avCorrect->setCulot($data['culot'] ?? null);
        $avCorrect->setPh($data['ph'] ?? null);
        $avCorrect->setDensiter($data['densiter'] ?? null);
        $avCorrect->setProteine($data['proteine'] ?? null);
        $avCorrect->setInitialPilote($data['initial_pilote'] ?? '');

        $this->avCorrectSojaRepository->save($avCorrect, true);

        return $this->json([
            'success' => true,
            'message' => 'Avant correction Soja enregistrée',
            'data' => [
                'av_correct_id' => $avCorrect->getId(),
                'tank' => $avCorrect->getTank(),
                'produit_fini' => $avCorrect->getProduitFini()
            ],
            'next_step' => 'Procéder aux corrections puis saisir Après Correction'
        ]);
    }

    /**
     * ÉTAPE 4 SOJA: Après Correction
     * POST /api/soja/of/{ofId}/apres-correction
     */
    #[Route('/of/{ofId}/apres-correction', name: 'apres_correction', methods: ['POST'])]
    public function createApresCorrection(int $ofId, Request $request): JsonResponse
    {
        $of = $this->ofRepository->find($ofId);
        
        if (!$of || $of->getProduction()->getType() !== 'soja') {
            return $this->json(['success' => false, 'message' => 'OF Soja non trouvé'], Response::HTTP_NOT_FOUND);
        }

        // Vérifier qu'il y a bien eu une avant correction
        $avCorrect = $this->avCorrectSojaRepository->findOneBy(['of' => $of]);
        if (!$avCorrect) {
            return $this->json([
                'success' => false,
                'message' => 'Avant correction requise'
            ], Response::HTTP_BAD_REQUEST);
        }

        $data = json_decode($request->getContent(), true);

        $apCorrect = new ApCorrectSoja();
        $apCorrect->setOf($of);
        $apCorrect->setDate(new \DateTime());
        $apCorrect->setTank($data['tank'] ?? null);
        $apCorrect->setEauAjouter($data['eau_ajouter'] ?? null);
        $apCorrect->setMatiere($data['matiere'] ?? null);
        $apCorrect->setProduitFini($data['produit_fini'] ?? null);
        $apCorrect->setEsTank($data['es_tank'] ?? null);
        $apCorrect->setCulot($data['culot'] ?? null);
        $apCorrect->setPh($data['ph'] ?? null);
        $apCorrect->setDensiter($data['densiter'] ?? null);
        $apCorrect->setProteine($data['proteine'] ?? null);
        $apCorrect->setInitialPilote($data['initial_pilote'] ?? '');

        $this->apCorrectSojaRepository->save($apCorrect, true);

        return $this->json([
            'success' => true,
            'message' => 'Après correction Soja enregistrée',
            'data' => [
                'ap_correct_id' => $apCorrect->getId(),
                'tank' => $apCorrect->getTank(),
                'produit_fini' => $apCorrect->getProduitFini()
            ],
            'next_step' => 'Saisir les données Okara'
        ]);
    }

    /**
     * ÉTAPE 5 SOJA: Okara (sous-produits)
     * POST /api/soja/of/{ofId}/okara
     */
    #[Route('/of/{ofId}/okara', name: 'okara_create', methods: ['POST'])]
    public function createOkara(int $ofId, Request $request): JsonResponse
    {
        $of = $this->ofRepository->find($ofId);
        
        if (!$of || $of->getProduction()->getType() !== 'soja') {
            return $this->json(['success' => false, 'message' => 'OF Soja non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        $okara = new Okara();
        $okara->setOf($of);
        $okara->setQuantite($data['quantite'] ?? null);
        $okara->setHumidite($data['humidite'] ?? null);
        $okara->setDestination($data['destination'] ?? '');
        $okara->setDateCollecte(new \DateTime());

        $this->okaraRepository->save($okara, true);

        return $this->json([
            'success' => true,
            'message' => 'Données Okara enregistrées',
            'data' => [
                'okara_id' => $okara->getId(),
                'quantite' => $okara->getQuantite(),
                'destination' => $okara->getDestination()
            ],
            'next_step' => 'Workflow Soja terminé - Prêt pour finalisation avec double validation'
        ]);
    }

    /**
     * STATUT: Statut complet du workflow Soja
     * GET /api/soja/of/{ofId}/status
     */
    #[Route('/of/{ofId}/status', name: 'workflow_status', methods: ['GET'])]
    public function getWorkflowStatus(int $ofId): JsonResponse
    {
        $of = $this->ofRepository->find($ofId);
        
        if (!$of || $of->getProduction()->getType() !== 'soja') {
            return $this->json(['success' => false, 'message' => 'OF Soja non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $status = [
            'of_id' => $of->getId(),
            'of_numero' => $of->getNumero(),
            'production_type' => 'soja',
            'steps' => [
                'haccp' => $this->haccpRepository->findOneBy(['of' => $of]) !== null,
                'analyses' => $this->analyseSojaRepository->countByOF($of),
                'avant_correction' => $this->avCorrectSojaRepository->findOneBy(['of' => $of]) !== null,
                'apres_correction' => $this->apCorrectSojaRepository->findOneBy(['of' => $of]) !== null,
                'okara' => $this->okaraRepository->findOneBy(['of' => $of]) !== null
            ]
        ];

        $completed = array_sum([
            $status['steps']['haccp'] ? 1 : 0,
            $status['steps']['analyses'] > 0 ? 1 : 0,
            $status['steps']['avant_correction'] ? 1 : 0,
            $status['steps']['apres_correction'] ? 1 : 0,
            $status['steps']['okara'] ? 1 : 0
        ]);

        $status['completion_percentage'] = ($completed / 5) * 100;
        $status['ready_for_finalization'] = $completed === 5;

        return $this->json([
            'success' => true,
            'data' => $status
        ]);
    }

    private function isProductionEnCours(OF $of): bool
    {
        return $of->getStatut() === 'en_cours';
    }
}
