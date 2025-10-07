<?php

namespace App\Controller;

use App\Entity\Okara;
use App\Form\OkaraType;
use App\Repository\OkaraRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/okara")
 */
class OkaraController extends AbstractController
{
    /**
     * @Route("/", name="okara_index", methods={"GET"})
     */
    public function index(OkaraRepository $okaraRepository): Response
    {
        return $this->render('okara/index.html.twig', [
            'okaras' => $okaraRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="okara_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $okara = new Okara();
        $form = $this->createForm(OkaraType::class, $okara);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($okara);
            $em->flush();
            return $this->redirectToRoute('okara_index');
        }

        return $this->render('okara/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="okara_show", methods={"GET"})
     */
    public function show(Okara $okara): Response
    {
        return $this->render('okara/show.html.twig', [
            'okara' => $okara,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="okara_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Okara $okara, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(OkaraType::class, $okara);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('okara_index');
        }

        return $this->render('okara/edit.html.twig', [
            'form' => $form->createView(),
            'okara' => $okara,
        ]);
    }

    /**
     * @Route("/{id}", name="okara_delete", methods={"POST"})
     */
    public function delete(Request $request, Okara $okara, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$okara->getId(), $request->request->get('_token'))) {
            $em->remove($okara);
            $em->flush();
        }
        return $this->redirectToRoute('okara_index');
    }
}
