<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/connexion", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/deconnexion", name="logout")
     */
    public function logout()
    {

    }

    /**
     * @Route("/inscription", name="registration")
     */
    public function registration(Request $request)
    {
        if ($this->getUser()) {
            return $this->redirectToRoute("homepage");
        }

        $user = new User;

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        
        return $this->render('security/registration.html.twig', [
            'registrationForm' => $form->createView(),
            'controller_name' => 'SecurityController',
        ]);
    }
}
