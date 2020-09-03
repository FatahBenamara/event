<?php

namespace App\Controller;

use App\Entity\Participe;
use App\Form\ParticipeType;
use App\Repository\ParticipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/participe")
 */
class ParticipeController extends AbstractController
{
    /**
     * @Route("/", name="participe_index", methods={"GET"})
     */
    public function index(ParticipeRepository $participeRepository): Response
    {
        return $this->render('participe/index.html.twig', [
            'participes' => $participeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="participe_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $participe = new Participe();
        $form = $this->createForm(ParticipeType::class, $participe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($participe);
            $entityManager->flush();

            return $this->redirectToRoute('participe_index');
        }

        return $this->render('participe/new.html.twig', [
            'participe' => $participe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="participe_show", methods={"GET"})
     */
    public function show(Participe $participe): Response
    {
        return $this->render('participe/show.html.twig', [
            'participe' => $participe,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="participe_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Participe $participe): Response
    {
        $form = $this->createForm(ParticipeType::class, $participe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('participe_index');
        }

        return $this->render('participe/edit.html.twig', [
            'participe' => $participe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="participe_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Participe $participe): Response
    {
        if ($this->isCsrfTokenValid('delete'.$participe->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($participe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('participe_index');
    }
}
