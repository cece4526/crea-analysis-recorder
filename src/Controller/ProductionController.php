<?php

namespace App\Controller;

use App\Entity\Production;
use App\Form\ProductionType;
use App\Repository\ProductionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/production")
 */
class ProductionController extends AbstractController
{
    /**
     * @Route("/", name="production_index", methods={"GET"})
     */
    public function index(ProductionRepository $productionRepository): Response
    {
        return $this->render('production/index.html.twig', [
            'productions' => $productionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="production_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $production = new Production();
        $form = $this->createForm(ProductionType::class, $production);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($production);
            $em->flush();
            return $this->redirectToRoute('production_index');
        }

        return $this->render('production/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="production_show", methods={"GET"})
     */
    public function show(Production $production): Response
    {
        return $this->render('production/show.html.twig', [
            'production' => $production,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="production_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Production $production, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ProductionType::class, $production);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('production_index');
        }

        return $this->render('production/edit.html.twig', [
            'form' => $form->createView(),
            'production' => $production,
        ]);
    }

    /**
     * @Route("/{id}", name="production_delete", methods={"POST"})
     */
    public function delete(Request $request, Production $production, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$production->getId(), $request->request->get('_token'))) {
            $em->remove($production);
            $em->flush();
        }
        return $this->redirectToRoute('production_index');
    }
}
