<?php

namespace App\Controller;

use App\Entity\OF;
use App\Form\OFType;
use App\Repository\OFRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/of")
 */
class OFController extends AbstractController
{
    /**
     * @Route("/", name="of_index", methods={"GET"})
     */
    public function index(OFRepository $ofRepository): Response
    {
        return $this->render('of/index.html.twig', [
            'ofs' => $ofRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="of_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $of = new OF();
        $form = $this->createForm(OFType::class, $of);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($of);
            $em->flush();
            return $this->redirectToRoute('of_index');
        }

        return $this->render('of/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="of_show", methods={"GET"})
     */
    public function show(OF $of): Response
    {
        return $this->render('of/show.html.twig', [
            'of' => $of,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="of_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, OF $of, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(OFType::class, $of);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('of_index');
        }

        return $this->render('of/edit.html.twig', [
            'form' => $form->createView(),
            'of' => $of,
        ]);
    }

    /**
     * @Route("/{id}", name="of_delete", methods={"POST"})
     */
    public function delete(Request $request, OF $of, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$of->getId(), $request->request->get('_token'))) {
            $em->remove($of);
            $em->flush();
        }
        return $this->redirectToRoute('of_index');
    }
}
