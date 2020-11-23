<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, \Swift_Mailer $mailer)
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactFormData = $form->getData();

            $message = (new \Swift_Message('You gotMail!'))
                ->setFrom($contactFormData['mail'])
                ->setTo('lorenzo.logos@ynov.com')
                ->setBody(
                    $contactFormData['message'],
                    'text/plain'
                );

            
            
            //$mailer->send($message); décommenter cette ligne pour envoyer un mail et penser a ajouter le lien smtp dans le .env
            //dump($contactFormData);

            $this->addFlash('success', "C'est envoyé !" );
            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/index.html.twig', [
            'our_form' => $form->createView(),
        ]);
    }
}
