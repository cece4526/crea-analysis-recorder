<?php

namespace App\Controller;

use App\Entity\AvCorrectSoja;
use App\Form\AvCorrectSojaType;
use App\Repository\AvCorrectSojaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/av-correct-soja")
 */
class AvCorrectSojaController extends AbstractController
{
    /**
     * @Route("/", name="av_correct_soja_index", methods={"GET"})
     */
    public function index(AvCorrectSojaRepository $repo): Response
    {
        return $this->render('av_correct_soja/index.html.twig', [
            'av_correct_sojas' => $repo->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="av_correct_soja_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $avCorrectSoja = new AvCorrectSoja();
        $form = $this->createForm(AvCorrectSojaType::class, $avCorrectSoja);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($avCorrectSoja);
            $em->flush();
            return $this->redirectToRoute('av_correct_soja_index');
        }
        return $this->render('av_correct_soja/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="av_correct_soja_show", methods={"GET"})
     */
    public function show(AvCorrectSoja $avCorrectSoja): Response
    {
        return $this->render('av_correct_soja/show.html.twig', [
            'av_correct_soja' => $avCorrectSoja,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="av_correct_soja_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, AvCorrectSoja $avCorrectSoja, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AvCorrectSojaType::class, $avCorrectSoja);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('av_correct_soja_index');
        }
        return $this->render('av_correct_soja/edit.html.twig', [
            'form' => $form->createView(),
            'av_correct_soja' => $avCorrectSoja,
        ]);
    }

    /**
     * @Route("/{id}", name="av_correct_soja_delete", methods={"POST"})
     */
    public function delete(Request $request, AvCorrectSoja $avCorrectSoja, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$avCorrectSoja->getId(), $request->request->get('_token'))) {
            $em->remove($avCorrectSoja);
            $em->flush();
        }
        return $this->redirectToRoute('av_correct_soja_index');
    }
}
