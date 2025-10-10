<?php

namespace App\Controller;

use App\Repository\AnalyseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Contrôleur pour les analyses et tableaux de bord
 * 
 * @Route("/analyse")
 */
class AnalyseController extends AbstractController
{
    private AnalyseRepository $analyseRepository;

    public function __construct(AnalyseRepository $analyseRepository)
    {
        $this->analyseRepository = $analyseRepository;
    }

    /**
     * Dashboard principal avec vue d'ensemble
     * 
     * @Route("/dashboard", name="analyse_dashboard")
     */
    public function dashboard(): Response
    {
        $statistiques = $this->analyseRepository->getStatistiquesGlobales();
        $productionsEnCours = $this->analyseRepository->getProductionsEnCours();
        $topProductions = $this->analyseRepository->getTopProductionsByTauxRealisation(5);
        $productionsParStatut = $this->analyseRepository->getProductionsParStatut();

        return $this->render('analyse/dashboard.html.twig', [
            'statistiques' => $statistiques,
            'productionsEnCours' => $productionsEnCours,
            'topProductions' => $topProductions,
            'productionsParStatut' => $productionsParStatut,
        ]);
    }

    /**
     * Vue détaillée du suivi de production
     * 
     * @Route("/suivi-production", name="analyse_suivi_production")
     */
    public function suiviProduction(): Response
    {
        $suiviProduction = $this->analyseRepository->getSuiviProduction();

        return $this->render('analyse/suivi_production.html.twig', [
            'productions' => $suiviProduction,
        ]);
    }

    /**
     * API JSON pour les données de suivi (pour graphiques)
     * 
     * @Route("/api/suivi-production", name="api_suivi_production", methods={"GET"})
     */
    public function apiSuiviProduction(): JsonResponse
    {
        $suiviProduction = $this->analyseRepository->getSuiviProduction();
        
        return $this->json([
            'success' => true,
            'data' => $suiviProduction,
            'count' => count($suiviProduction),
        ]);
    }

    /**
     * API pour les statistiques globales
     * 
     * @Route("/api/statistiques", name="api_statistiques", methods={"GET"})
     */
    public function apiStatistiques(): JsonResponse
    {
        $statistiques = $this->analyseRepository->getStatistiquesGlobales();
        
        return $this->json([
            'success' => true,
            'data' => $statistiques,
        ]);
    }

    /**
     * Productions problématiques (faible taux de réalisation)
     * 
     * @Route("/productions-problematiques", name="analyse_productions_problematiques")
     */
    public function productionsProblematiques(): Response
    {
        $productionsProblematiques = $this->analyseRepository->getProductionsProblematiques(80);

        return $this->render('analyse/productions_problematiques.html.twig', [
            'productions' => $productionsProblematiques,
            'seuil' => 80,
        ]);
    }

    /**
     * Détail d'une production spécifique
     * 
     * @Route("/production/{id}", name="analyse_detail_production", requirements={"id"="\d+"})
     */
    public function detailProduction(int $id): Response
    {
        $production = $this->analyseRepository->getDetailProduction($id);

        if (!$production) {
            throw $this->createNotFoundException('Production non trouvée');
        }

        return $this->render('analyse/detail_production.html.twig', [
            'production' => $production,
        ]);
    }

    /**
     * Fonction de recherche globale
     * 
     * @param Request $request La requête HTTP
     * @return Response La réponse avec les résultats de recherche
     * 
     * @Route("/recherche", name="analyse_recherche", methods={"GET", "POST"})
     */
    public function recherche(Request $request): Response
    {
        $searchTerm = $request->query->get('q', '');
        $results = [];

        if (!empty($searchTerm)) {
            $results = $this->analyseRepository->searchProductions($searchTerm);
        }

        return $this->render('analyse/recherche.html.twig', [
            'searchTerm' => $searchTerm,
            'results' => $results,
        ]);
    }
}
