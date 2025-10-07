<?php

namespace App\Controller;

use App\Entity\CuveCereales;
use App\Form\CuveCerealesType;
use App\Repository\CuveCerealesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cuve-cereales')]
class CuveCerealesController extends AbstractController
{
    #[Route('/', name: 'cuve_cereales_index', methods: ['GET'])]
    public function index(CuveCerealesRepository $repository): Response
    {
        return $this->render('cuve_cereales/index.html.twig', [
            'cuve_cereales' => $repository->findAll(),
        ]);
    }

    #[Route('/new', name: 'cuve_cereales_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $cuveCereales = new CuveCereales();
        $form = $this->createForm(CuveCerealesType::class, $cuveCereales);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($cuveCereales);
            $em->flush();
            return $this->redirectToRoute('cuve_cereales_index');
        }

        return $this->render('cuve_cereales/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'cuve_cereales_show', methods: ['GET'])]
    public function show(CuveCereales $cuveCereales): Response
    {
        return $this->render('cuve_cereales/show.html.twig', [
            'cuve_cereales' => $cuveCereales,
        ]);
    }

    #[Route('/{id}/edit', name: 'cuve_cereales_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CuveCereales $cuveCereales, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CuveCerealesType::class, $cuveCereales);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('cuve_cereales_index');
        }

        return $this->render('cuve_cereales/edit.html.twig', [
            'form' => $form->createView(),
            'cuve_cereales' => $cuveCereales,
        ]);
    }

    #[Route('/{id}', name: 'cuve_cereales_delete', methods: ['POST'])]
    public function delete(Request $request, CuveCereales $cuveCereales, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cuveCereales->getId(), $request->request->get('_token'))) {
            $em->remove($cuveCereales);
            $em->flush();
        }
        return $this->redirectToRoute('cuve_cereales_index');
    }
}
