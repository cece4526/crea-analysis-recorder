<?php

namespace App\Controller;

use App\Entity\QuantiteEnzyme;
use App\Form\QuantiteEnzymeType;
use App\Repository\QuantiteEnzymeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/quantite-enzyme')]
class QuantiteEnzymeController extends AbstractController
{
    #[Route('/', name: 'quantite_enzyme_index', methods: ['GET'])]
    public function index(QuantiteEnzymeRepository $repository): Response
    {
        return $this->render('quantite_enzyme/index.html.twig', [
            'quantite_enzymes' => $repository->findAll(),
        ]);
    }

    #[Route('/new', name: 'quantite_enzyme_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $quantiteEnzyme = new QuantiteEnzyme();
        $form = $this->createForm(QuantiteEnzymeType::class, $quantiteEnzyme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($quantiteEnzyme);
            $em->flush();
            return $this->redirectToRoute('quantite_enzyme_index');
        }

        return $this->render('quantite_enzyme/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'quantite_enzyme_show', methods: ['GET'])]
    public function show(QuantiteEnzyme $quantiteEnzyme): Response
    {
        return $this->render('quantite_enzyme/show.html.twig', [
            'quantite_enzyme' => $quantiteEnzyme,
        ]);
    }

    #[Route('/{id}/edit', name: 'quantite_enzyme_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, QuantiteEnzyme $quantiteEnzyme, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(QuantiteEnzymeType::class, $quantiteEnzyme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('quantite_enzyme_index');
        }

        return $this->render('quantite_enzyme/edit.html.twig', [
            'form' => $form->createView(),
            'quantite_enzyme' => $quantiteEnzyme,
        ]);
    }

    #[Route('/{id}', name: 'quantite_enzyme_delete', methods: ['POST'])]
    public function delete(Request $request, QuantiteEnzyme $quantiteEnzyme, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$quantiteEnzyme->getId(), $request->request->get('_token'))) {
            $em->remove($quantiteEnzyme);
            $em->flush();
        }
        return $this->redirectToRoute('quantite_enzyme_index');
    }
}
