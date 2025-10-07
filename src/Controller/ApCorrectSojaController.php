<?php

namespace App\Controller;

use App\Entity\ApCorrectSoja;
use App\Form\ApCorrectSojaType;
use App\Repository\ApCorrectSojaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ap-correct-soja")
 */
class ApCorrectSojaController extends AbstractController
{
    /**
     * @Route("/", name="ap_correct_soja_index", methods={"GET"})
     */
    public function index(ApCorrectSojaRepository $apCorrectSojaRepository): Response
    {
        return $this->render('ap_correct_soja/index.html.twig', [
            'ap_correct_sojas' => $apCorrectSojaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="ap_correct_soja_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $apCorrectSoja = new ApCorrectSoja();
        $form = $this->createForm(ApCorrectSojaType::class, $apCorrectSoja);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($apCorrectSoja);
            $entityManager->flush();

            return $this->redirectToRoute('ap_correct_soja_index');
        }

        return $this->render('ap_correct_soja/new.html.twig', [
            'ap_correct_soja' => $apCorrectSoja,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ap_correct_soja_show", methods={"GET"})
     */
    public function show(ApCorrectSoja $apCorrectSoja): Response
    {
        return $this->render('ap_correct_soja/show.html.twig', [
            'ap_correct_soja' => $apCorrectSoja,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ap_correct_soja_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, ApCorrectSoja $apCorrectSoja, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ApCorrectSojaType::class, $apCorrectSoja);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('ap_correct_soja_index');
        }

        return $this->render('ap_correct_soja/edit.html.twig', [
            'ap_correct_soja' => $apCorrectSoja,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ap_correct_soja_delete", methods={"POST"})
     */
    public function delete(Request $request, ApCorrectSoja $apCorrectSoja, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$apCorrectSoja->getId(), $request->request->get('_token'))) {
            $entityManager->remove($apCorrectSoja);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ap_correct_soja_index');
    }
}
