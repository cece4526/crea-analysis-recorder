<?php

namespace App\Controller;

use App\Entity\ApCorrectCereales;
use App\Form\ApCorrectCerealesType;
use App\Repository\ApCorrectCerealesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ap-correct-cereales")
 */
class ApCorrectCerealesController extends AbstractController
{
    /**
     * @Route("/", name="ap_correct_cereales_index", methods={"GET"})
     */
    public function index(ApCorrectCerealesRepository $repo): Response
    {
        return $this->render('ap_correct_cereales/index.html.twig', [
            'ap_correct_cereales' => $repo->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="ap_correct_cereales_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $apCorrectCereales = new ApCorrectCereales();
        $form = $this->createForm(ApCorrectCerealesType::class, $apCorrectCereales);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($apCorrectCereales);
            $em->flush();
            return $this->redirectToRoute('ap_correct_cereales_index');
        }
        return $this->render('ap_correct_cereales/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ap_correct_cereales_show", methods={"GET"})
     */
    public function show(ApCorrectCereales $apCorrectCereales): Response
    {
        return $this->render('ap_correct_cereales/show.html.twig', [
            'ap_correct_cereales' => $apCorrectCereales,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ap_correct_cereales_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, ApCorrectCereales $apCorrectCereales, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ApCorrectCerealesType::class, $apCorrectCereales);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('ap_correct_cereales_index');
        }
        return $this->render('ap_correct_cereales/edit.html.twig', [
            'form' => $form->createView(),
            'ap_correct_cereales' => $apCorrectCereales,
        ]);
    }

    /**
     * @Route("/{id}", name="ap_correct_cereales_delete", methods={"POST"})
     */
    public function delete(Request $request, ApCorrectCereales $apCorrectCereales, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$apCorrectCereales->getId(), $request->request->get('_token'))) {
            $em->remove($apCorrectCereales);
            $em->flush();
        }
        return $this->redirectToRoute('ap_correct_cereales_index');
    }
}
