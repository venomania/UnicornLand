<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Roles;
use App\Entity\Users;
use App\Entity\States;
use App\Entity\Articles;
use App\Entity\Comments;
use App\Entity\Categories;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsersFixtures extends Fixture
{
    /** @var UserPasswordEncoderInterface */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        
        $userRole = new Roles();
        $userRole->setName('ROLE_USER');
        $manager->persist($userRole);

        $adminRole = new Roles();
        $adminRole->setName('ROLE_ADMIN');
        $manager->persist($adminRole);

        $manager->flush();

        $approved = new States();
        $approved->setState('Approved');
        $manager->persist($approved);

        $waiting = new States();
        $waiting->setState('Waiting');
        $manager->persist($waiting);

        $disapproved = new States();
        $disapproved->setState('Disapproved');
        $manager->persist($disapproved);

        $manager->flush();

        $user = new Users();
        $user->setFirstname($faker->firstName)
            ->setLastname($faker->lastName)
            ->setMail('user@user.com')
            ->setPassword($this->encoder->encodePassword($user, 'user'))
            ->addUserRole($userRole)
        ;

        $manager->persist($user);
        $manager->flush();

        $admin = new Users();
        $admin->setFirstname($faker->firstName)
            ->setLastname($faker->lastName)
            ->setMail('admin@admin.com')
            ->setPassword($this->encoder->encodePassword($user, 'admin'))
            ->addUserRole($userRole)
            ->addUserRole($adminRole)
        ;

        $manager->persist($admin);
        $manager->flush();

        $users = [];
        for ($j=0; $j < 20; $j++) { 
            $gender = ($j %2 == 0) ? 'male': 'female';
            $user = new Users();
            $user->setFirstname($faker->firstName($gender))
                ->setLastname($faker->lastName)
                ->setMail($faker->safeEmail)
                ->setPassword($this->encoder->encodePassword($user, 'user'))
                ->addUserRole($userRole)
            ;       
            $manager->persist($user);
            $users[] = $user;
        }

        $manager->flush();

        $category1 = new Categories();
        $category1->setName('Les licornes de Polynésies');

        $manager->persist($category1);
        $manager->flush();

        $category2 = new Categories();
        $category2->setName('Les licornes sans corne');

        $manager->persist($category2);
        $manager->flush();

        $category3 = new Categories();
        $category3->setName('Les licornes malades');

        $manager->persist($category3);
        $manager->flush();

        $categories = [$category1, $category2, $category3];

        $nbUsers = count($users) -1;

        $articles = [];
        for ($k=0; $k < 20; $k++) { 
            $article = new Articles();
            $article->setDate($faker->dateTimeBetween('-6 month', '+6 month', 'Europe/Paris'))
            ->setTitle($faker->word)
            ->setSubTitle($faker->word)
            ->setImage($faker->imageUrl($width=640, $height=480))
            ->setArticleContent($faker->text($maxNbChars = 200))
            ->setVisibility(true)
            ->setCategory($categories[$faker->numberBetween(0, 2)])
            ->setUser($users[$faker->numberBetween(0, $nbUsers)])
            ;

            $manager->persist($article);
            $articles[] = $article;
        }

        $manager->flush();

        $nbArticles = count($articles) -1;

        for ($f=0; $f < 80; $f++) { 
            $comments = new Comments();
            $comments->setComment($faker->text($maxNbChars = 200))
            ->setDate($faker->dateTimeBetween('-6 month', '+6 month', 'Europe/Paris'))
            ->setState($waiting)
            ->setArticle($articles[$faker->numberBetween(0, $nbArticles)])
            ->setUser($users[$faker->numberBetween(0, $nbUsers)])
            ;

            $manager->persist($comments);
        }

        $manager->flush();
    }
}
