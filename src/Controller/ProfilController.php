<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UsersType;
use App\Entity\Articles;
use App\Entity\Comments;
use App\Form\UsersEditType;
use App\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

    /**
     * @Route("/profil")
     */
class ProfilController extends AbstractController
{
    /**
     * @Route("/{id}", name="profil_accueil", methods={"GET", "POST"})
     */
    public function index(Users $user, UsersRepository $usersRepository, Request $request): Response
    {
        $userLogged = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $RAW_QUERY = 'SELECT * FROM article_user_share INNER JOIN articles ON article_user_share.articles_id = articles.id where users_id = '.$userLogged->getId().';';
        
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();

        $articlesShare = $statement->fetchAll();

        $RAW_QUERY = 'SELECT * FROM article_user_like INNER JOIN articles ON article_user_like.articles_id = articles.id where users_id = '.$userLogged->getId().';';
        
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();

        $articlesLike = $statement->fetchAll();
        
        $RAW_QUERY = 'SELECT * FROM comments INNER JOIN articles ON comments.article_id = articles.id where comments.user_id = '.$userLogged->getId().' GROUP BY articles.id;';
        
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();

        $articlesComment = $statement->fetchAll();

        $form = $this->createForm(UsersEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect("profil");
        }

        return $this->render('profil/index.html.twig', [
            'user' => $userLogged,
            'articleShare' => $articlesShare,
            'articleLike' => $articlesLike,
            'articleComment' => $articlesComment,
            'users' => $user,
            'form' => $form->createView(),
            ]);
    }
}
