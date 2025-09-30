<?php

namespace App\Controller;

use App\Entity\Echantillons;
use App\Form\EchantillonsType;
use App\Repository\EchantillonsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/echantillons")
 */
class EchantillonsController extends AbstractController
{
    /**
     * @Route("/", name="echantillons_index", methods={"GET"})
     */
    public function index(EchantillonsRepository $echantillonsRepository): Response
    {
        return $this->render('echantillons/index.html.twig', [
            'echantillons' => $echantillonsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="echantillons_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $echantillon = new Echantillons();
        $form = $this->createForm(EchantillonsType::class, $echantillon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($echantillon);
            $em->flush();
            return $this->redirectToRoute('echantillons_index');
        }

        return $this->render('echantillons/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="echantillons_show", methods={"GET"})
     */
    public function show(Echantillons $echantillon): Response
    {
        return $this->render('echantillons/show.html.twig', [
            'echantillon' => $echantillon,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="echantillons_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Echantillons $echantillon, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(EchantillonsType::class, $echantillon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('echantillons_index');
        }

        return $this->render('echantillons/edit.html.twig', [
            'form' => $form->createView(),
            'echantillon' => $echantillon,
        ]);
    }

    /**
     * @Route("/{id}", name="echantillons_delete", methods={"POST"})
     */
    public function delete(Request $request, Echantillons $echantillon, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$echantillon->getId(), $request->request->get('_token'))) {
            $em->remove($echantillon);
            $em->flush();
        }
        return $this->redirectToRoute('echantillons_index');
    }
}
