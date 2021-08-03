<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditEmailFormType;
use App\Form\EditPasswordFormType;
use App\Form\EditUsernameFormType;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/connexion", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('homepage');
        }

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
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher)
    {
        if ($this->getUser()) {
            return $this->redirectToRoute("homepage");
        }

        $user = new User;

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
          $hash = $hasher->hashPassword($user, $user->getPassword());
          $roles = ["ROLE_USER"];

          $user->setPassword($hash);
          $user->setRoles($roles);

          $manager->persist($user);
          $manager->flush();

          $this->addFlash('success', "Votre compte a bien été créé");

          return $this->redirectToRoute('login');
        }

        return $this->render('security/registration.html.twig', [
            'registrationForm' => $form->createView(),
            'controller_name' => 'SecurityController',
        ]);
    }

    /**
     * @Route("/compte", name="user-account")
     */
    public function userAccount()
    {
        return $this->render('security/user-account.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

    public function editUsername(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(EditUsernameFormType::class, $user);
        $form->handleRequest($request);

        return $this->render('security/_edit-username-form.html.twig', [
            'usernameForm' => $form->createView()
        ]);
    }

    public function editEmail(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(EditEmailFormType::class, $user);
        $form->handleRequest($request);

        return $this->render('security/_edit-email-form.html.twig', [
            'emailForm' => $form->createView()
        ]);
    }

    public function editPassword(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(EditPasswordFormType::class, $user);
        $form->handleRequest($request);

        return $this->render('security/_edit-password-form.html.twig', [
            'passwordForm' => $form->createView()
        ]);
    }
}
