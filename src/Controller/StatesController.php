<?php

namespace App\Controller;

use App\Entity\States;
use App\Form\StatesType;
use App\Repository\StatesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/states")
 */
class StatesController extends AbstractController
{
    /**
     * @Route("/", name="states_index", methods={"GET"})
     */
    public function index(StatesRepository $statesRepository): Response
    {
        return $this->render('states/index.html.twig', [
            'states' => $statesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="states_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $state = new States();
        $form = $this->createForm(StatesType::class, $state);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($state);
            $entityManager->flush();

            return $this->redirectToRoute('states_index');
        }

        return $this->render('states/new.html.twig', [
            'state' => $state,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="states_show", methods={"GET"})
     */
    public function show(States $state): Response
    {
        return $this->render('states/show.html.twig', [
            'state' => $state,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="states_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, States $state): Response
    {
        $form = $this->createForm(StatesType::class, $state);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('states_index');
        }

        return $this->render('states/edit.html.twig', [
            'state' => $state,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="states_delete", methods={"DELETE"})
     */
    public function delete(Request $request, States $state): Response
    {
        if ($this->isCsrfTokenValid('delete'.$state->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($state);
            $entityManager->flush();
        }

        return $this->redirectToRoute('states_index');
    }
}
