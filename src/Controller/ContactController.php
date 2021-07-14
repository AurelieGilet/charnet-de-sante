<?php

namespace App\Controller;

use App\Form\ContactFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactFormData = $form->getData();

            $message = (new Email())
                ->from($contactFormData['email'])
                ->to('charnetdesante@gmail.com')
                ->subject('Le Charnet de Santé - Formulaire de contact')
                ->text('Expéditeur : ' .$contactFormData['name']. ' ' .$contactFormData['firstname'].\PHP_EOL.
                    $contactFormData['message'],
                    'text/plain');

            $mailer->send($message);

            $this->addFlash('success', 'Votre message a bien été envoyé');

            return $this->redirectToRoute('contact');

        }

        return $this->render('homepage/contact.html.twig', [
            'contactForm' => $form->createView(),
            'controller_name' => 'ContactController',
        ]);
    }
}
