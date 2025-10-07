<?php

namespace App\Controller;

use App\Entity\AnalyseSoja;
use App\Form\AnalyseSojaType;
use App\Repository\AnalyseSojaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/analyse-soja")
 */
class AnalyseSojaController extends AbstractController
{
    /**
     * @Route("/", name="analyse_soja_index", methods={"GET"})
     */
    public function index(AnalyseSojaRepository $repository): Response
    {
        return $this->render('analyse_soja/index.html.twig', [
            'analyse_sojas' => $repository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="analyse_soja_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $analyseSoja = new AnalyseSoja();
        $form = $this->createForm(AnalyseSojaType::class, $analyseSoja);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($analyseSoja);
            $em->flush();
            return $this->redirectToRoute('analyse_soja_index');
        }

        return $this->render('analyse_soja/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="analyse_soja_show", methods={"GET"})
     */
    public function show(AnalyseSoja $analyseSoja): Response
    {
        return $this->render('analyse_soja/show.html.twig', [
            'analyse_soja' => $analyseSoja,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="analyse_soja_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, AnalyseSoja $analyseSoja, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AnalyseSojaType::class, $analyseSoja);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('analyse_soja_index');
        }

        return $this->render('analyse_soja/edit.html.twig', [
            'form' => $form->createView(),
            'analyse_soja' => $analyseSoja,
        ]);
    }

    /**
     * @Route("/{id}", name="analyse_soja_delete", methods={"POST"})
     */
    public function delete(Request $request, AnalyseSoja $analyseSoja, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$analyseSoja->getId(), $request->request->get('_token'))) {
            $em->remove($analyseSoja);
            $em->flush();
        }
        return $this->redirectToRoute('analyse_soja_index');
    }
}
