<?php

namespace App\Controller;

use App\Entity\AvCorrectCereales;
use App\Form\AvCorrectCerealesType;
use App\Repository\AvCorrectCerealesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/av-correct-cereales")
 */
class AvCorrectCerealesController extends AbstractController
{
    /**
     * @Route("/", name="av_correct_cereales_index", methods={"GET"})
     */
    public function index(AvCorrectCerealesRepository $repo): Response
    {
        return $this->render('av_correct_cereales/index.html.twig', [
            'av_correct_cereales' => $repo->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="av_correct_cereales_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $avCorrectCereales = new AvCorrectCereales();
        $form = $this->createForm(AvCorrectCerealesType::class, $avCorrectCereales);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($avCorrectCereales);
            $em->flush();
            return $this->redirectToRoute('av_correct_cereales_index');
        }
        return $this->render('av_correct_cereales/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="av_correct_cereales_show", methods={"GET"})
     */
    public function show(AvCorrectCereales $avCorrectCereales): Response
    {
        return $this->render('av_correct_cereales/show.html.twig', [
            'av_correct_cereales' => $avCorrectCereales,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="av_correct_cereales_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, AvCorrectCereales $avCorrectCereales, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AvCorrectCerealesType::class, $avCorrectCereales);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('av_correct_cereales_index');
        }
        return $this->render('av_correct_cereales/edit.html.twig', [
            'form' => $form->createView(),
            'av_correct_cereales' => $avCorrectCereales,
        ]);
    }

    /**
     * @Route("/{id}", name="av_correct_cereales_delete", methods={"POST"})
     */
    public function delete(Request $request, AvCorrectCereales $avCorrectCereales, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$avCorrectCereales->getId(), $request->request->get('_token'))) {
            $em->remove($avCorrectCereales);
            $em->flush();
        }
        return $this->redirectToRoute('av_correct_cereales_index');
    }
}
