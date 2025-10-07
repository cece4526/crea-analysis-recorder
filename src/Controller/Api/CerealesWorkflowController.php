<?php

namespace App\Controller\Api;

use App\Entity\OF;
use App\Entity\HACCP;
use App\Entity\CuveCereales;
use App\Entity\HeureEnzyme;
use App\Entity\DecanteurCereales;
use App\Entity\AvCorrectCereales;
use App\Entity\ApCorrectCereales;
use App\Repository\OFRepository;
use App\Repository\HACCPRepository;
use App\Repository\CuveCerealesRepository;
use App\Repository\HeureEnzymeRepository;
use App\Repository\DecanteurCerealesRepository;
use App\Repository\AvCorrectCerealesRepository;
use App\Repository\ApCorrectCerealesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur pour le workflow complet des productions CÉRÉALES
 * Gère le processus: Production → OF → HACCP → Cuves → Décanteurs → Corrections → Validation
 */
#[Route('/api/cereales-workflow', name: 'api_cereales_workflow_')]
class CerealesWorkflowController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private OFRepository $ofRepository,
        private HACCPRepository $haccpRepository,
        private CuveCerealesRepository $cuveCerealesRepository,
        private HeureEnzymeRepository $heureEnzymeRepository,
        private DecanteurCerealesRepository $decanteurCerealesRepository,
        private AvCorrectCerealesRepository $avCorrectCerealesRepository,
        private ApCorrectCerealesRepository $apCorrectCerealesRepository
    ) {
    }

    /**
     * Étape 1: Créer le contrôle HACCP pour un OF CÉRÉALES
     * POST /api/cereales-workflow/of/{ofId}/haccp
     */
    #[Route('/of/{ofId}/haccp', name: 'create_haccp', methods: ['POST'])]
    public function createHACCP(int $ofId, Request $request): JsonResponse
    {
        $of = $this->ofRepository->find($ofId);
        
        if (!$of) {
            return $this->json([
                'success' => false,
                'message' => 'OF non trouvé'
            ], Response::HTTP_NOT_FOUND);
        }

        // Vérifier que c'est une production CÉRÉALES
        if ($of->getProduction()->getType() !== 'cereales') {
            return $this->json([
                'success' => false,
                'message' => 'Cet OF n\'est pas pour une production CÉRÉALES',
                'type_production' => $of->getProduction()->getType()
            ], Response::HTTP_BAD_REQUEST);
        }

        $data = json_decode($request->getContent(), true);

        try {
            $haccp = new HACCP();
            $haccp->setOf($of);
            $haccp->setHeureControle(new \DateTime($data['heure_controle']));
            $haccp->setPointControle($data['point_controle']);
            $haccp->setTemperature($data['temperature']);
            $haccp->setDuree($data['duree']);
            $haccp->setConformite($data['conformite']);
            $haccp->setObservations($data['observations'] ?? null);

            $this->entityManager->persist($haccp);

            // Mettre à jour le statut de l'OF
            if ($of->getStatut() === 'nouveau') {
                $of->setStatut('haccp_fait');
            }

            $this->entityManager->flush();

            return $this->json([
                'success' => true,
                'message' => 'Contrôle HACCP créé avec succès',
                'data' => [
                    'haccp_id' => $haccp->getId(),
                    'of_statut' => $of->getStatut(),
                    'next_step' => 'cuves_cereales'
                ]
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Erreur lors de la création du HACCP',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Étape 2: Créer une cuve CÉRÉALES
     * POST /api/cereales-workflow/of/{ofId}/cuve
     */
    #[Route('/of/{ofId}/cuve', name: 'create_cuve', methods: ['POST'])]
    public function createCuveCereales(int $ofId, Request $request): JsonResponse
    {
        $of = $this->ofRepository->find($ofId);
        
        if (!$of) {
            return $this->json([
                'success' => false,
                'message' => 'OF non trouvé'
            ], Response::HTTP_NOT_FOUND);
        }

        // Vérifier qu'il y a eu un HACCP
        $haccpCount = $this->haccpRepository->count(['of' => $of]);
        if ($haccpCount === 0) {
            return $this->json([
                'success' => false,
                'message' => 'HACCP requis avant de créer des cuves',
                'required_step' => 'haccp'
            ], Response::HTTP_BAD_REQUEST);
        }

        $data = json_decode($request->getContent(), true);

        try {
            $cuve = new CuveCereales();
            $cuve->setOf($of);
            $cuve->setNumeroCuve($data['numero_cuve']);
            $cuve->setHeureDebut(new \DateTime($data['heure_debut']));
            
            if (isset($data['heure_fin'])) {
                $cuve->setHeureFin(new \DateTime($data['heure_fin']));
            }
            
            $cuve->setPh($data['ph']);
            $cuve->setTemperature($data['temperature']);
            $cuve->setVolume($data['volume']);
            $cuve->setPression($data['pression']);
            $cuve->setConformite($data['conformite']);
            $cuve->setObservations($data['observations'] ?? null);

            $this->entityManager->persist($cuve);

            // Mettre à jour le statut de l'OF
            if ($of->getStatut() === 'haccp_fait') {
                $of->setStatut('cuves_en_cours');
            }

            $this->entityManager->flush();

            return $this->json([
                'success' => true,
                'message' => 'Cuve céréales créée avec succès',
                'data' => [
                    'cuve_id' => $cuve->getId(),
                    'numero_cuve' => $cuve->getNumeroCuve(),
                    'of_statut' => $of->getStatut(),
                    'next_step' => 'decanteurs_ou_autres_cuves'
                ]
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la cuve',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Étape 3: Créer une hydrolyse enzymatique CÉRÉALES (entre cuves et décanteurs)
     * POST /api/cereales-workflow/of/{ofId}/hydrolyse-enzyme
     */
    #[Route('/of/{ofId}/hydrolyse-enzyme', name: 'create_hydrolyse_enzyme', methods: ['POST'])]
    public function createHydrolyseEnzyme(int $ofId, Request $request): JsonResponse
    {
        $of = $this->ofRepository->find($ofId);
        
        if (!$of) {
            return $this->json([
                'success' => false,
                'message' => 'OF non trouvé'
            ], Response::HTTP_NOT_FOUND);
        }

        // Vérifier qu'il y a au moins une cuve avant l'hydrolyse
        $cuveCount = $this->cuveCerealesRepository->count(['of' => $of]);
        if ($cuveCount === 0) {
            return $this->json([
                'success' => false,
                'message' => 'Au moins une cuve requise avant l\'hydrolyse enzymatique',
                'required_step' => 'cuves'
            ], Response::HTTP_BAD_REQUEST);
        }

        $data = json_decode($request->getContent(), true);

        try {
            $heureEnzyme = new HeureEnzyme();
            $heureEnzyme->setOf($of);
            $heureEnzyme->setHeureDebut(new \DateTime($data['heure_debut']));
            
            if (isset($data['heure_fin'])) {
                $heureEnzyme->setHeureFin(new \DateTime($data['heure_fin']));
            }
            
            $heureEnzyme->setTypeEnzyme($data['type_enzyme']);
            $heureEnzyme->setQuantiteEnzyme($data['quantite_enzyme']);
            $heureEnzyme->setTemperatureHydrolyse($data['temperature_hydrolyse']);
            $heureEnzyme->setPhInitial($data['ph_initial']);
            
            if (isset($data['ph_final'])) {
                $heureEnzyme->setPhFinal($data['ph_final']);
            }
            
            $heureEnzyme->setDureeHydrolyse($data['duree_hydrolyse']);
            $heureEnzyme->setOperateur($data['operateur']);
            $heureEnzyme->setConformite($data['conformite']);
            $heureEnzyme->setObservations($data['observations'] ?? null);
            
            if (isset($data['efficacite_hydrolyse'])) {
                $heureEnzyme->setEfficaciteHydrolyse($data['efficacite_hydrolyse']);
            }

            $this->entityManager->persist($heureEnzyme);

            // Mettre à jour le statut de l'OF
            if ($of->getStatut() === 'cuves_en_cours') {
                $of->setStatut('hydrolyse_en_cours');
            }

            $this->entityManager->flush();

            return $this->json([
                'success' => true,
                'message' => 'Hydrolyse enzymatique créée avec succès',
                'data' => [
                    'hydrolyse_id' => $heureEnzyme->getId(),
                    'type_enzyme' => $heureEnzyme->getTypeEnzyme(),
                    'duree_hydrolyse' => $heureEnzyme->getDureeHydrolyse(),
                    'efficacite' => $heureEnzyme->getEfficaciteHydrolyse(),
                    'of_statut' => $of->getStatut(),
                    'next_step' => 'decanteurs'
                ]
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Erreur lors de la création de l\'hydrolyse enzymatique',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Étape 4: Créer un décanteur CÉRÉALES (après hydrolyse enzymatique)
     * POST /api/cereales-workflow/of/{ofId}/decanteur
     */
    #[Route('/of/{ofId}/decanteur', name: 'create_decanteur', methods: ['POST'])]
    public function createDecanteurCereales(int $ofId, Request $request): JsonResponse
    {
        $of = $this->ofRepository->find($ofId);
        
        if (!$of) {
            return $this->json([
                'success' => false,
                'message' => 'OF non trouvé'
            ], Response::HTTP_NOT_FOUND);
        }

        // Vérifier qu'il y a au moins une cuve ET une hydrolyse enzymatique
        $cuveCount = $this->cuveCerealesRepository->count(['of' => $of]);
        $hydrolyseCount = $this->heureEnzymeRepository->count(['of' => $of]);
        
        if ($cuveCount === 0) {
            return $this->json([
                'success' => false,
                'message' => 'Au moins une cuve requise avant les décanteurs',
                'required_step' => 'cuves'
            ], Response::HTTP_BAD_REQUEST);
        }
        
        if ($hydrolyseCount === 0) {
            return $this->json([
                'success' => false,
                'message' => 'Hydrolyse enzymatique requise avant les décanteurs',
                'required_step' => 'hydrolyse_enzyme'
            ], Response::HTTP_BAD_REQUEST);
        }

        $data = json_decode($request->getContent(), true);

        try {
            $decanteur = new DecanteurCereales();
            $decanteur->setOf($of);
            $decanteur->setNumeroDecanteur($data['numero_decanteur']);
            $decanteur->setHeureDebut(new \DateTime($data['heure_debut']));
            
            if (isset($data['heure_fin'])) {
                $decanteur->setHeureFin(new \DateTime($data['heure_fin']));
            }
            
            $decanteur->setPh($data['ph']);
            $decanteur->setTemperature($data['temperature']);
            $decanteur->setVolume($data['volume']);
            $decanteur->setClarte($data['clarte']);
            $decanteur->setConformite($data['conformite']);
            $decanteur->setObservations($data['observations'] ?? null);

            $this->entityManager->persist($decanteur);

            // Mettre à jour le statut de l'OF
            if ($of->getStatut() === 'cuves_en_cours') {
                $of->setStatut('decanteurs_en_cours');
            }

            $this->entityManager->flush();

            return $this->json([
                'success' => true,
                'message' => 'Décanteur céréales créé avec succès',
                'data' => [
                    'decanteur_id' => $decanteur->getId(),
                    'numero_decanteur' => $decanteur->getNumeroDecanteur(),
                    'of_statut' => $of->getStatut(),
                    'next_step' => 'corrections_avant_ou_apres'
                ]
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Erreur lors de la création du décanteur',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Étape 4: Correction AVANT production CÉRÉALES
     * POST /api/cereales-workflow/of/{ofId}/correction-avant
     */
    #[Route('/of/{ofId}/correction-avant', name: 'create_correction_avant', methods: ['POST'])]
    public function createCorrectionAvant(int $ofId, Request $request): JsonResponse
    {
        $of = $this->ofRepository->find($ofId);
        
        if (!$of) {
            return $this->json([
                'success' => false,
                'message' => 'OF non trouvé'
            ], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        try {
            $correction = new AvCorrectCereales();
            $correction->setOf($of);
            $correction->setHeureCorrection(new \DateTime($data['heure_correction']));
            $correction->setTypeCorrection($data['type_correction']);
            $correction->setEquipement($data['equipement'] ?? null);
            $correction->setValeurAvant($data['valeur_avant']);
            $correction->setValeurApres($data['valeur_apres']);
            $correction->setQuantiteAjoutee($data['quantite_ajoutee']);
            $correction->setOperateur($data['operateur']);
            $correction->setObservations($data['observations'] ?? null);

            $this->entityManager->persist($correction);

            // Mettre à jour le statut si nécessaire
            if (in_array($of->getStatut(), ['cuves_en_cours', 'decanteurs_en_cours'])) {
                $of->setStatut('corrections_av_faites');
            }

            $this->entityManager->flush();

            return $this->json([
                'success' => true,
                'message' => 'Correction AVANT créée avec succès',
                'data' => [
                    'correction_id' => $correction->getId(),
                    'type_correction' => $correction->getTypeCorrection(),
                    'of_statut' => $of->getStatut(),
                    'next_step' => 'corrections_apres_ou_validation'
                ]
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la correction AVANT',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Étape 5: Correction APRÈS production CÉRÉALES
     * POST /api/cereales-workflow/of/{ofId}/correction-apres
     */
    #[Route('/of/{ofId}/correction-apres', name: 'create_correction_apres', methods: ['POST'])]
    public function createCorrectionApres(int $ofId, Request $request): JsonResponse
    {
        $of = $this->ofRepository->find($ofId);
        
        if (!$of) {
            return $this->json([
                'success' => false,
                'message' => 'OF non trouvé'
            ], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        try {
            $correction = new ApCorrectCereales();
            $correction->setOf($of);
            $correction->setHeureCorrection(new \DateTime($data['heure_correction']));
            $correction->setTypeCorrection($data['type_correction']);
            $correction->setEquipement($data['equipement'] ?? null);
            $correction->setValeurAvant($data['valeur_avant']);
            $correction->setValeurApres($data['valeur_apres']);
            $correction->setQuantiteAjoutee($data['quantite_ajoutee']);
            $correction->setOperateur($data['operateur']);
            $correction->setObservations($data['observations'] ?? null);

            $this->entityManager->persist($correction);

            // Mettre à jour le statut
            $of->setStatut('corrections_ap_faites');

            $this->entityManager->flush();

            return $this->json([
                'success' => true,
                'message' => 'Correction APRÈS créée avec succès',
                'data' => [
                    'correction_id' => $correction->getId(),
                    'type_correction' => $correction->getTypeCorrection(),
                    'of_statut' => $of->getStatut(),
                    'next_step' => 'validation_finale'
                ]
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la correction APRÈS',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Obtenir le statut complet d'un OF CÉRÉALES
     * GET /api/cereales-workflow/of/{ofId}/status
     */
    #[Route('/of/{ofId}/status', name: 'get_of_status', methods: ['GET'])]
    public function getOFStatus(int $ofId): JsonResponse
    {
        $of = $this->ofRepository->find($ofId);
        
        if (!$of) {
            return $this->json([
                'success' => false,
                'message' => 'OF non trouvé'
            ], Response::HTTP_NOT_FOUND);
        }

        if ($of->getProduction()->getType() !== 'cereales') {
            return $this->json([
                'success' => false,
                'message' => 'Cet OF n\'est pas pour une production CÉRÉALES'
            ], Response::HTTP_BAD_REQUEST);
        }

        // Récupérer toutes les données
        $haccp = $this->haccpRepository->findByOF($of->getId());
        $cuves = $this->cuveCerealesRepository->findByOFOrderedByTime($of->getId());
        $hydrolyses = $this->heureEnzymeRepository->findByOFOrderedByTime($of->getId());
        $decanteurs = $this->decanteurCerealesRepository->findByOFOrderedByTime($of->getId());
        $avCorrections = $this->avCorrectCerealesRepository->findByOF($of->getId());
        $apCorrections = $this->apCorrectCerealesRepository->findByOF($of->getId());

        // Calculer les étapes complétées
        $etapesCompletees = [];
        if (!empty($haccp)) $etapesCompletees[] = 'haccp';
        if (!empty($cuves)) $etapesCompletees[] = 'cuves';
        if (!empty($hydrolyses)) $etapesCompletees[] = 'hydrolyse_enzyme';
        if (!empty($decanteurs)) $etapesCompletees[] = 'decanteurs';
        if (!empty($avCorrections)) $etapesCompletees[] = 'corrections_avant';
        if (!empty($apCorrections)) $etapesCompletees[] = 'corrections_apres';

        // Déterminer la prochaine étape
        $prochaineEtape = $this->determineNextStep($etapesCompletees, $of->getStatut());

        return $this->json([
            'success' => true,
            'message' => 'Statut OF CÉRÉALES récupéré',
            'data' => [
                'of' => [
                    'id' => $of->getId(),
                    'numero' => $of->getNumero(),
                    'statut' => $of->getStatut(),
                    'nature' => $of->getNature()
                ],
                'production' => [
                    'id' => $of->getProduction()->getId(),
                    'type' => $of->getProduction()->getType(),
                    'date' => $of->getProduction()->getDate()->format('Y-m-d H:i:s')
                ],
                'etapes_completees' => $etapesCompletees,
                'prochaine_etape' => $prochaineEtape,
                'counts' => [
                    'haccp' => count($haccp),
                    'cuves' => count($cuves),
                    'hydrolyses' => count($hydrolyses),
                    'decanteurs' => count($decanteurs),
                    'corrections_avant' => count($avCorrections),
                    'corrections_apres' => count($apCorrections)
                ],
                'pourcentage_completion' => $this->calculateCompletionPercentage($etapesCompletees)
            ]
        ]);
    }

    /**
     * Finaliser un OF CÉRÉALES (double validation)
     * POST /api/cereales-workflow/of/{ofId}/finalize
     */
    #[Route('/of/{ofId}/finalize', name: 'finalize_of', methods: ['POST'])]
    public function finalizeOF(int $ofId, Request $request): JsonResponse
    {
        $of = $this->ofRepository->find($ofId);
        
        if (!$of) {
            return $this->json([
                'success' => false,
                'message' => 'OF non trouvé'
            ], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        // Vérifier les étapes minimales requises
        $haccpCount = $this->haccpRepository->count(['of' => $of]);
        $cuveCount = $this->cuveCerealesRepository->count(['of' => $of]);

        if ($haccpCount === 0) {
            return $this->json([
                'success' => false,
                'message' => 'HACCP requis pour finaliser l\'OF',
                'missing_steps' => ['haccp']
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($cuveCount === 0) {
            return $this->json([
                'success' => false,
                'message' => 'Au moins une cuve requise pour finaliser l\'OF',
                'missing_steps' => ['cuves']
            ], Response::HTTP_BAD_REQUEST);
        }

        // Double validation requise
        if (!isset($data['confirmation']) || !$data['confirmation']) {
            return $this->json([
                'success' => false,
                'message' => 'Confirmation requise pour finaliser l\'OF',
                'required_field' => 'confirmation'
            ], Response::HTTP_BAD_REQUEST);
        }

        if (!isset($data['operateur_validation']) || empty($data['operateur_validation'])) {
            return $this->json([
                'success' => false,
                'message' => 'Opérateur de validation requis',
                'required_field' => 'operateur_validation'
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $of->setStatut('finalise');
            $of->setDateFinalisation(new \DateTime());
            $of->setOperateurValidation($data['operateur_validation']);
            $of->setCommentairesFinalisation($data['commentaires'] ?? null);

            $this->entityManager->flush();

            return $this->json([
                'success' => true,
                'message' => 'OF CÉRÉALES finalisé avec succès',
                'data' => [
                    'of_id' => $of->getId(),
                    'statut' => $of->getStatut(),
                    'date_finalisation' => $of->getDateFinalisation()->format('Y-m-d H:i:s'),
                    'operateur_validation' => $of->getOperateurValidation(),
                    'pdf_available' => true,
                    'pdf_url' => '/api/pdf/of/' . $of->getId() . '/generate'
                ]
            ]);

        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Erreur lors de la finalisation',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Détermine la prochaine étape du workflow
     */
    private function determineNextStep(array $etapesCompletees, string $statut): ?string
    {
        if (!in_array('haccp', $etapesCompletees)) {
            return 'haccp';
        }
        
        if (!in_array('cuves', $etapesCompletees)) {
            return 'cuves';
        }
        
        if (!in_array('hydrolyse_enzyme', $etapesCompletees)) {
            return 'hydrolyse_enzyme';
        }
        
        if (!in_array('decanteurs', $etapesCompletees)) {
            return 'decanteurs';
        }
        
        if (!in_array('corrections_avant', $etapesCompletees) && !in_array('corrections_apres', $etapesCompletees)) {
            return 'corrections';
        }
        
        if ($statut !== 'finalise') {
            return 'finalisation';
        }
        
        return null;
    }

    /**
     * Calcule le pourcentage de completion
     */
    private function calculateCompletionPercentage(array $etapesCompletees): int
    {
        $etapesTotales = ['haccp', 'cuves', 'hydrolyse_enzyme', 'decanteurs', 'corrections_avant', 'corrections_apres'];
        $completed = 0;
        
        foreach ($etapesTotales as $etape) {
            if (in_array($etape, $etapesCompletees)) {
                $completed++;
            }
        }
        
        return (int) (($completed / count($etapesTotales)) * 100);
    }
}
