<?php

namespace App\Controller;

use App\Entity\DecanteurCereales;
use App\Form\DecanteurCerealesType;
use App\Repository\DecanteurCerealesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/decanteur-cereales")
 */
class DecanteurCerealesController extends AbstractController
{
    /**
     * @Route("/", name="decanteur_cereales_index", methods={"GET"})
     */
    public function index(DecanteurCerealesRepository $repository): Response
    {
        return $this->render('decanteur_cereales/index.html.twig', [
            'decanteur_cereales' => $repository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="decanteur_cereales_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $decanteurCereales = new DecanteurCereales();
        $form = $this->createForm(DecanteurCerealesType::class, $decanteurCereales);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($decanteurCereales);
            $em->flush();
            return $this->redirectToRoute('decanteur_cereales_index');
        }

        return $this->render('decanteur_cereales/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="decanteur_cereales_show", methods={"GET"})
     */
    public function show(DecanteurCereales $decanteurCereales): Response
    {
        return $this->render('decanteur_cereales/show.html.twig', [
            'decanteur_cereales' => $decanteurCereales,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="decanteur_cereales_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, DecanteurCereales $decanteurCereales, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(DecanteurCerealesType::class, $decanteurCereales);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('decanteur_cereales_index');
        }

        return $this->render('decanteur_cereales/edit.html.twig', [
            'form' => $form->createView(),
            'decanteur_cereales' => $decanteurCereales,
        ]);
    }

    /**
     * @Route("/{id}", name="decanteur_cereales_delete", methods={"POST"})
     */
    public function delete(Request $request, DecanteurCereales $decanteurCereales, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$decanteurCereales->getId(), $request->request->get('_token'))) {
            $em->remove($decanteurCereales);
            $em->flush();
        }
        return $this->redirectToRoute('decanteur_cereales_index');
    }
}
