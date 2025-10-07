<?php

namespace App\Controller;

use App\Entity\HeureEnzyme;
use App\Form\HeureEnzymeType;
use App\Repository\HeureEnzymeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    #[Route('/new', name: 'heure_enzyme_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $heureEnzyme = new HeureEnzyme();
        $form = $this->createForm(HeureEnzymeType::class, $heureEnzyme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($heureEnzyme);
            $em->flush();
            return $this->redirectToRoute('heure_enzyme_index');
        }

        return $this->render('heure_enzyme/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'heure_enzyme_show', methods: ['GET'])]
    public function show(HeureEnzyme $heureEnzyme): Response
    {
        return $this->render('heure_enzyme/show.html.twig', [
            'heure_enzyme' => $heureEnzyme,
        ]);
    }

    #[Route('/{id}/edit', name: 'heure_enzyme_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, HeureEnzyme $heureEnzyme, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(HeureEnzymeType::class, $heureEnzyme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('heure_enzyme_index');
        }

        return $this->render('heure_enzyme/edit.html.twig', [
            'form' => $form->createView(),
            'heure_enzyme' => $heureEnzyme,
        ]);
    }

    #[Route('/{id}', name: 'heure_enzyme_delete', methods: ['POST'])]
    public function delete(Request $request, HeureEnzyme $heureEnzyme, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$heureEnzyme->getId(), $request->request->get('_token'))) {
            $em->remove($heureEnzyme);
            $em->flush();
        }
        return $this->redirectToRoute('heure_enzyme_index');
    }
}
