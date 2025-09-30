<?php

namespace App\Controller;

use App\Entity\Enzyme;
use App\Form\EnzymeType;
use App\Repository\EnzymeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/enzyme")
 */
class EnzymeController extends AbstractController
{
    /**
     * @Route("/", name="enzyme_index", methods={"GET"})
     */
    public function index(EnzymeRepository $repository): Response
    {
        return $this->render('enzyme/index.html.twig', [
            'enzymes' => $repository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="enzyme_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $enzyme = new Enzyme();
        $form = $this->createForm(EnzymeType::class, $enzyme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($enzyme);
            $em->flush();
            return $this->redirectToRoute('enzyme_index');
        }

        return $this->render('enzyme/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="enzyme_show", methods={"GET"})
     */
    public function show(Enzyme $enzyme): Response
    {
        return $this->render('enzyme/show.html.twig', [
            'enzyme' => $enzyme,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="enzyme_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Enzyme $enzyme, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(EnzymeType::class, $enzyme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('enzyme_index');
        }

        return $this->render('enzyme/edit.html.twig', [
            'form' => $form->createView(),
            'enzyme' => $enzyme,
        ]);
    }

    /**
     * @Route("/{id}", name="enzyme_delete", methods={"POST"})
     */
    public function delete(Request $request, Enzyme $enzyme, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$enzyme->getId(), $request->request->get('_token'))) {
            $em->remove($enzyme);
            $em->flush();
        }
        return $this->redirectToRoute('enzyme_index');
    }
}
