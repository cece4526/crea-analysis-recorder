<?php

namespace App\Controller;

use App\Entity\CuveCereales;
use App\Entity\OF;
use App\Form\CuveCerealesType;
use App\Repository\CuveCerealesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cuve-cereales')]
class CuveCerealesController extends AbstractController
{
    #[Route('/', name: 'cuve_cereales_index', methods: ['GET'])]
    public function index(CuveCerealesRepository $repository): Response
    {
        return $this->render('cuve_cereales/index.html.twig', [
            'cuve_cereales' => $repository->findAll(),
        ]);
    }

    #[Route('/new', name: 'cuve_cereales_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $cuveCereales = new CuveCereales();
        $form = $this->createForm(CuveCerealesType::class, $cuveCereales);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($cuveCereales);
            $em->flush();
            return $this->redirectToRoute('cuve_cereales_index');
        }

        return $this->render('cuve_cereales/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'cuve_cereales_show', methods: ['GET'])]
    public function show(CuveCereales $cuveCereales): Response
    {
        return $this->render('cuve_cereales/show.html.twig', [
            'cuve_cereales' => $cuveCereales,
        ]);
    }

    #[Route('/{id}/edit', name: 'cuve_cereales_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CuveCereales $cuveCereales, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CuveCerealesType::class, $cuveCereales);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('cuve_cereales_index');
        }

        return $this->render('cuve_cereales/edit.html.twig', [
            'form' => $form->createView(),
            'cuve_cereales' => $cuveCereales,
        ]);
    }

    #[Route('/create', name: 'cuve_cereales_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        try {
            $data = json_decode($request->getContent(), true);
            
            // Debug: Log des données reçues dans fichier spécifique
            $logMessage = "[" . date('Y-m-d H:i:s') . "] CUVE DEBUG - Données reçues: " . json_encode($data) . "\n";
            file_put_contents('debug_cuve.log', $logMessage, FILE_APPEND);
            
            if (!$data) {
                $logMessage = "[" . date('Y-m-d H:i:s') . "] CUVE DEBUG - Données JSON invalides\n";
                file_put_contents('debug_cuve.log', $logMessage, FILE_APPEND);
                return $this->json([
                    'success' => false,
                    'message' => 'Données JSON invalides'
                ], 400);
            }

            // Récupérer l'OF
            $of = null;
            if (isset($data['of_id'])) {
                $logMessage = "[" . date('Y-m-d H:i:s') . "] CUVE DEBUG - Recherche OF avec ID: " . $data['of_id'] . "\n";
                file_put_contents('debug_cuve.log', $logMessage, FILE_APPEND);
                $of = $entityManager->getRepository(OF::class)->find($data['of_id']);
                if (!$of) {
                    $logMessage = "[" . date('Y-m-d H:i:s') . "] CUVE DEBUG - OF non trouvé avec ID: " . $data['of_id'] . "\n";
                    file_put_contents('debug_cuve.log', $logMessage, FILE_APPEND);
                    return $this->json([
                        'success' => false,
                        'message' => 'OF non trouvé avec ID: ' . $data['of_id']
                    ], 400);
                } else {
                    $logMessage = "[" . date('Y-m-d H:i:s') . "] CUVE DEBUG - OF trouvé: #" . $of->getId() . " - " . $of->getProduit() . "\n";
                    file_put_contents('debug_cuve.log', $logMessage, FILE_APPEND);
                }
            } else {
                $logMessage = "[" . date('Y-m-d H:i:s') . "] CUVE DEBUG - Pas d'of_id dans les données\n";
                file_put_contents('debug_cuve.log', $logMessage, FILE_APPEND);
                return $this->json([
                    'success' => false,
                    'message' => 'ID de l\'OF manquant'
                ], 400);
            }

            // Créer la nouvelle entité CuveCereales
            $cuveCereales = new CuveCereales();
            $cuveCereales->setOf($of);
            $cuveCereales->setCuve($data['cuve'] ?? null);
            $cuveCereales->setDebitEnzyme($data['debit_enzyme'] ?? null);
            $cuveCereales->setTemperatureHydrolise($data['temperature_hydrolise'] ?? null);
            $cuveCereales->setMatiere($data['matiere'] ?? null);
            $cuveCereales->setQuantiteEnzyme($data['quantite_enzyme'] ?? null);
            $cuveCereales->setControlVerre(isset($data['control_verre']) ? (bool)$data['control_verre'] : null);
            $cuveCereales->setInitialPilote($data['initial_pilote'] ?? null);
            $cuveCereales->setCreatedAt(new \DateTime());

            // Persister en base
            $entityManager->persist($cuveCereales);
            $entityManager->flush();

            return $this->json([
                'success' => true,
                'message' => 'Cuve céréales enregistrée avec succès',
                'id' => $cuveCereales->getId()
            ]);
            
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Erreur lors de l\'enregistrement: ' . $e->getMessage()
            ], 500);
        }
    }

    #[Route('/{id}', name: 'cuve_cereales_delete', methods: ['POST'])]
    public function delete(Request $request, CuveCereales $cuveCereales, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cuveCereales->getId(), $request->request->get('_token'))) {
            $em->remove($cuveCereales);
            $em->flush();
        }
        return $this->redirectToRoute('cuve_cereales_index');
    }
}
