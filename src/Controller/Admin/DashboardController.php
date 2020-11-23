<?php

namespace App\Controller\Admin;

use App\Entity\Users;
use App\Entity\Articles;
use App\Entity\Comments;
use App\Repository\UsersRepository;
use App\Repository\ArticlesRepository;
use App\Repository\CommentsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;


class DashboardController extends AbstractDashboardController
{

    protected $ArticlesRepository;
    protected $UsersRepository;
    protected $CommentsRepository;

    public function __construct(ArticlesRepository $ArticlesRepository, UsersRepository $UsersRepository, CommentsRepository $CommentsRepository){

        $this->ArticlesRepository = $ArticlesRepository;
        $this->UsersRepository = $UsersRepository;
        $this->CommentsRepository = $CommentsRepository;
    }

    /**
     * @Route("/admin", name="admin")

     */
    public function index(): Response
    {

        $repoUsers = $this->getDoctrine()->getRepository(Users::class);
        $users = $repoUsers->findAll();

        $repoArticles = $this->getDoctrine()->getRepository(Articles::class);
        $articles = $repoArticles->findAll();

        $repoComments = $this->getDoctrine()->getRepository(Comments::class);
        $comments = $repoComments->findAll();

        return $this->render('bundles/easyAdminBundle/welcome.html.twig',[
            'countAllUsers' => $this->UsersRepository->countAllUsers(),
            'user' => $users,
            'article' => $articles,
            'comments' => $comments,
            'lastsArticles' => $this->ArticlesRepository->getLastsArticles(),
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Unicorn Land');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Accueil', 'fa fa-home');
        yield MenuItem::linktoCrud('Articles', 'fas fa-book', Articles::class);
        yield MenuItem::linktoCrud('Commentaires', 'fas fa-comment', Comments::class);
        // yield MenuItem::linkToCrud('The Label', 'icon class', EntityClass::class);
    }
}
