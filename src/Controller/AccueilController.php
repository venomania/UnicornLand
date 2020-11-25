<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Repository\ArticlesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface; 

class AccueilController extends AbstractController
{
    /**
     * @Route("/accueil", name="accueil", methods={"GET"})
     */
    public function index(Request $request ,PaginatorInterface $paginator): Response
    {
        $donnees = $this->getDoctrine()->getRepository(Articles::class)->findAll();
        $articles = $paginator->paginate(
            $donnees,
            $request->query->getInt('page',1),
            10
        );

        return $this->render('accueil/index.html.twig', [
            'articles' => $articles,
        ]);
    }
}
