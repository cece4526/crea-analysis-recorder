<?php

namespace App\Controller;

use App\Entity\HeureEnzyme;
use App\Entity\CuveCereales;
use App\Repository\HeureEnzymeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/heure-enzyme')]
class HeureEnzymeController extends AbstractController
{
    #[Route('/', name: 'heure_enzyme_index', methods: ['GET'])]
    public function index(HeureEnzymeRepository $repository): Response
    {
        return $this->render('heure_enzyme/index.html.twig', [
            'heure_enzymes' => $repository->findAll(),
        ]);
    }

    #[Route('/create', name: 'heure_enzyme_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        try {
            // Debug: Log des données reçues
            $allData = $request->request->all();
            error_log('HEURE_ENZYME DEBUG - Données reçues: ' . json_encode($allData));
            
            $ofData = $request->request->get('of_id');
            $heureData = $request->request->get('heure');
            $cuveCerealesId = $request->request->get('cuve_cereales_id');
            
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
            }
            
            // Vérifier s'il existe déjà un enregistrement pour cet OF
            $existingHeure = $em->getRepository(HeureEnzyme::class)->findOneBy(['ofId' => $ofId]);
            
            if ($existingHeure) {
                // Mettre à jour l'enregistrement existant
                $heureEnzyme = $existingHeure;
                error_log('HEURE_ENZYME DEBUG - Mise à jour existante ID: ' . $heureEnzyme->getId());
            } else {
                // Créer un nouveau enregistrement
                $heureEnzyme = new HeureEnzyme();
                error_log('HEURE_ENZYME DEBUG - Création nouvel enregistrement');
            }
            
            // Créer l'objet DateTime pour l'heure
            if ($heureData) {
                $heure = \DateTime::createFromFormat('H:i', $heureData);
                if ($heure) {
                    $heureEnzyme->setHeure($heure);
                }
            }
            
            $heureEnzyme->setOfId($ofId);
            
            // Lier à la cuve céréales si spécifiée
            if ($cuveCerealesId) {
                $cuveCereales = $em->getRepository(CuveCereales::class)->find($cuveCerealesId);
                if ($cuveCereales) {
                    $heureEnzyme->setCuveCereales($cuveCereales);
                    $heureEnzyme->setCuveCerealesId($cuveCerealesId);
                }
            }
            
            $em->persist($heureEnzyme);
            $em->flush();
            
            $isUpdate = $existingHeure !== null;
            $message = $isUpdate ? 
                'Heure d\'enzyme mise à jour avec succès.' : 
                'Heure d\'enzyme enregistrée avec succès.';
            
            return $this->json([
                'success' => true,
                'message' => $message,
                'action' => $isUpdate ? 'updated' : 'created'
            ]);
            
        } catch (\Exception $e) {
            error_log('HEURE_ENZYME ERROR: ' . $e->getMessage());
            return $this->json([
                'success' => false,
                'message' => 'Erreur lors de l\'enregistrement : ' . $e->getMessage()
            ], 400);
        }
    }
    
    #[Route('/api/get/{ofId}', name: 'heure_enzyme_api_get', methods: ['GET'])]
    public function getByOf(int $ofId, EntityManagerInterface $em): JsonResponse
    {
        try {
            $heureEnzyme = $em->getRepository(HeureEnzyme::class)->findOneBy(['ofId' => $ofId]);
            
            if (!$heureEnzyme) {
                return $this->json(['exists' => false]);
            }
            
            return $this->json([
                'exists' => true,
                'data' => [
                    'id' => $heureEnzyme->getId(),
                    'heure' => $heureEnzyme->getHeure() ? $heureEnzyme->getHeure()->format('H:i') : null,
                    'of_id' => $heureEnzyme->getOfId(),
                    'created_at' => $heureEnzyme->getCreatedAt() ? $heureEnzyme->getCreatedAt()->format('Y-m-d H:i:s') : null,
                    'cuve_cereales_id' => $heureEnzyme->getCuveCerealesId(),
                    'cuve_numero' => $heureEnzyme->getCuveCereales() ? $heureEnzyme->getCuveCereales()->getCuve() : null
                ]
            ]);
            
        } catch (\Exception $e) {
            return $this->json([
                'exists' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
