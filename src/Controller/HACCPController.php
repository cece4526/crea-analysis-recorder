<?php

namespace App\Controller;

use App\Entity\HACCP;
use App\Form\HACCPType;
use App\Repository\HACCPRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/haccp')]
class HACCPController extends AbstractController
{
    #[Route('/', name: 'haccp_index', methods: ['GET'])]
    public function index(HACCPRepository $haccpRepository): Response
    {
        return $this->render('haccp/index.html.twig', [
            'haccps' => $haccpRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'haccp_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $haccp = new HACCP();
        $form = $this->createForm(HACCPType::class, $haccp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($haccp);
            $em->flush();
            return $this->redirectToRoute('haccp_index');
        }

        return $this->render('haccp/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'haccp_show', methods: ['GET'])]
    public function show(HACCP $haccp): Response
    {
        return $this->render('haccp/show.html.twig', [
            'haccp' => $haccp,
        ]);
    }

    #[Route('/{id}/edit', name: 'haccp_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, HACCP $haccp, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(HACCPType::class, $haccp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('haccp_index');
        }

        return $this->render('haccp/edit.html.twig', [
            'form' => $form->createView(),
            'haccp' => $haccp,
        ]);
    }

    #[Route('/{id}', name: 'haccp_delete', methods: ['POST'])]
    public function delete(Request $request, HACCP $haccp, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$haccp->getId(), $request->request->get('_token'))) {
            $em->remove($haccp);
            $em->flush();
        }
        return $this->redirectToRoute('haccp_index');
    }
}
