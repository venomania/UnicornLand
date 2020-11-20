<?php

namespace App\Controller;

use App\Entity\Articles;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccueilController extends AbstractController
{
    /**
     * @Route("/accueil", name="accueil")
     */
    public function index()
    {

        $repoArticle = $this->getDoctrine()->getRepository(Articles::class);
        $articles = $repoArticle->findAll();

        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
            'article' => $articles,
        ]);
    }
}
