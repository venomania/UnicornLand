<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\States;
use App\Form\LikeType;
use App\Form\ShareType;
use Doctrine\ORM\Query;
use App\Entity\Articles;
use App\Entity\Comments;
use App\Form\ArticlesType;
use App\Form\CommentsType;
use App\Repository\StatesRepository;
use App\Repository\ArticlesRepository;
use App\Repository\CommentsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/articles")
 */
class ArticlesController extends AbstractController
{
    /**
     * @Route("/", name="articles_index", methods={"GET"})
     */
    public function index(ArticlesRepository $articlesRepository): Response
    {
        return $this->render('articles/index.html.twig', [
            'articles' => $articlesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="articles_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $article = new Articles();
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('articles_index');
        }

        return $this->render('articles/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="articles_show", methods={"GET", "POST"})
     */
    public function show(Articles $article, CommentsRepository $commentsRepository, StatesRepository $statesRepository, Request $request, $id): Response
    {
        $states = $this->getDoctrine()
            ->getRepository(States::class)
            ->findBy(array('state' => 'Waiting'));

        $articles = $this->getDoctrine()
            ->getRepository(Articles::class)
            ->find($id);

        $userLogged = $this->getUser();

        $comment = new Comments();
        $form = $this->createForm(CommentsType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $comment                    
            ->setState($states[0])
            ->setArticle($articles)
            ->setUser($userLogged)
            ->setDate(new \DateTime('now'))
            ;
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirect($id);
        }

        $formLike = $this->createForm(LikeType::class, $userLogged);
        $formLike->handleRequest($request);

        if ($formLike->isSubmitted()) {
            $entityManager = $this->getDoctrine()->getManager();
            $userLogged                  
            ->addLike($articles)
            ;
            $entityManager->persist($userLogged);
            $entityManager->flush();

            return $this->redirect($id);
        }

        $formShare = $this->createForm(ShareType::class, $userLogged);
        $formShare->handleRequest($request);

        if ($formShare->isSubmitted()) {
            $entityManager = $this->getDoctrine()->getManager();
            $userLogged                 
            ->addShare($articles)
            ;
            $entityManager->persist($userLogged);
            $entityManager->flush();

            return $this->redirect($id);
        }

        return $this->render('articles/show.html.twig', [
            'article' => $article,
            'formLike' => $formLike->createView(),
            'formShare' => $formShare->createView(),
            'comments' => $commentsRepository->findBy(array('article' => $id)),
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="articles_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Articles $article): Response
    {
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('articles_index');
        }

        return $this->render('articles/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="articles_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Articles $article): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('articles_index');
    }
}
