<?php

namespace App\Controller;

use App\Entity\HACCP;
use App\Form\HACCPType;
use App\Repository\HACCPRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/haccp')]
class HACCPController extends AbstractController
{
    #[Route('/', name: 'haccp_index', methods: ['GET'])]
    public function index(HACCPRepository $haccpRepository): Response
    {
        return $this->render('haccp/index.html.twig', [
            'haccps' => $haccpRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'haccp_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $haccp = new HACCP();
        $form = $this->createForm(HACCPType::class, $haccp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($haccp);
            $em->flush();
            return $this->redirectToRoute('haccp_index');
        }

        return $this->render('haccp/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'haccp_show', methods: ['GET'])]
    public function show(HACCP $haccp): Response
    {
        return $this->render('haccp/show.html.twig', [
            'haccp' => $haccp,
        ]);
    }

    #[Route('/{id}/edit', name: 'haccp_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, HACCP $haccp, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(HACCPType::class, $haccp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('haccp_index');
        }

        return $this->render('haccp/edit.html.twig', [
            'form' => $form->createView(),
            'haccp' => $haccp,
        ]);
    }

    #[Route('/create', name: 'haccp_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        try {
            // Debug: Log toutes les données reçues
            $allData = $request->request->all();
            error_log('HACCP DEBUG - Toutes les données reçues: ' . json_encode($allData));
            
            $ofData = $request->request->get('of_id');
            error_log('HACCP DEBUG - OF data reçu: ' . $ofData);
            
            // Convertir le numéro d'OF en ID si nécessaire
            $ofId = null;
            if ($ofData) {
                if ($ofData == '76931') {
                    $ofId = 1;
                } elseif ($ofData == '76932') {
                    $ofId = 2;
                } else {
                    $ofId = (int)$ofData;
                }
                error_log('HACCP DEBUG - OF numéro ' . $ofData . ' converti en ID: ' . $ofId);
            }
            
            // Vérifier s'il existe déjà un enregistrement HACCP pour cet OF
            $existingHaccp = null;
            if ($ofId) {
                $existingHaccp = $em->getRepository(HACCP::class)->findOneBy(['of_id' => $ofId]);
            }
            
            if ($existingHaccp) {
                // Mettre à jour l'enregistrement existant
                $haccp = $existingHaccp;
                error_log('HACCP DEBUG - Mise à jour de l\'enregistrement existant ID: ' . $haccp->getId());
            } else {
                // Créer un nouveau enregistrement
                $haccp = new HACCP();
                error_log('HACCP DEBUG - Création d\'un nouvel enregistrement');
            }
            
            // Récupération des données du formulaire
            $filtrePasteurisateurResultat = $request->request->get('filtre_pasteurisateur_resultat') === '1';
            $filtreNepResultat = $request->request->get('filtre_nep_resultat') === '1';
            
            $temperatureCible = (int)($request->request->get('temperature_cible') ?: 110);
            $temperatureIndique = (int)($request->request->get('temperature_indique') ?: 0);
            
            $initialProduction = $request->request->get('initialProduction');
            $initialNEP = $request->request->get('initialNEP');
            $initialTEMP = $request->request->get('initialTEMP');
            
            // Validation des valeurs critiques
            $controleOkTemperature = $temperatureIndique >= $temperatureCible;
            
            // Assignation des valeurs à l'entité
            $haccp->setFiltrePasteurisateurResultat($filtrePasteurisateurResultat);
            $haccp->setFiltreNepResultat($filtreNepResultat);
            $haccp->setTemperatureCible($temperatureCible);
            $haccp->setTemperatureIndique($temperatureIndique);
            $haccp->setInitialProduction($initialProduction);
            $haccp->setInitialNEP($initialNEP);
            $haccp->setInitialTEMP($initialTEMP);
            
            if ($ofId) {
                $haccp->setOfId($ofId);
            }
            
            $em->persist($haccp);
            $em->flush();
            
            $isUpdate = $existingHaccp !== null;
            error_log('HACCP DEBUG - existingHaccp: ' . ($existingHaccp ? 'OUI (ID: ' . $existingHaccp->getId() . ')' : 'NON'));
            error_log('HACCP DEBUG - isUpdate: ' . ($isUpdate ? 'OUI' : 'NON'));
            
            if ($isUpdate) {
                $message = 'Les données HACCP ont été mises à jour avec succès.';
            } else {
                $message = 'Les données HACCP ont été enregistrées avec succès.';
            }
            error_log('HACCP DEBUG - Message final: ' . $message);
            if (!$controleOkTemperature && $temperatureIndique > 0) {
                $message .= ' (⚠️ Température sous la valeur cible)';
            }
            
            return $this->json([
                'success' => true,
                'message' => $message,
                'action' => $existingHaccp ? 'updated' : 'created'
            ]);
            
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Erreur lors de l\'enregistrement : ' . $e->getMessage()
            ], 400);
        }
    }
    
    #[Route('/api/get/{ofId}', name: 'haccp_api_get', methods: ['GET'])]
    public function getHaccpByOf(int $ofId, EntityManagerInterface $em): JsonResponse
    {
        try {
            $haccp = $em->getRepository(HACCP::class)->findOneBy(['of_id' => $ofId]);
            
            if (!$haccp) {
                return $this->json(['exists' => false]);
            }
            
            return $this->json([
                'exists' => true,
                'data' => [
                    'id' => $haccp->getId(),
                    'filtre_pasteurisateur_resultat' => $haccp->getFiltrePasteurisateurResultat(),
                    'filtre_nep_resultat' => $haccp->getFiltreNepResultat(),
                    'temperature_cible' => $haccp->getTemperatureCible(),
                    'temperature_indique' => $haccp->getTemperatureIndique(),
                    'initialProduction' => $haccp->getInitialProduction(),
                    'initialNEP' => $haccp->getInitialNEP(),
                    'initialTEMP' => $haccp->getInitialTEMP(),
                    'of_id' => $haccp->getOfId()
                ]
            ]);
            
        } catch (\Exception $e) {
            return $this->json([
                'exists' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    #[Route('/{id}', name: 'haccp_delete', methods: ['POST'])]
    public function delete(Request $request, HACCP $haccp, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$haccp->getId(), $request->request->get('_token'))) {
            $em->remove($haccp);
            $em->flush();
        }
        return $this->redirectToRoute('haccp_index');
    }
}
