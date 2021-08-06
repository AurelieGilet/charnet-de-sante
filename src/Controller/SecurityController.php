<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditEmailFormType;
use App\Form\EditPasswordFormType;
use App\Form\EditUsernameFormType;
use App\Form\RegistrationFormType;
use App\Form\EditProfilPictureFormType;
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

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername, 
            'error' => $error
        ]);
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
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
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
     * @Route("/espace-utilisateur/compte", name="user-account")
     */
    public function userAccount()
    {
        return $this->render('security/user_account.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

    /**
     * @Route("/espace-utilisateur/compte/editer-nom-utilisateur", name="edit-username")
     */
    public function editUsername(Request $request, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(EditUsernameFormType::class, $user, [
            'action' => $this->generateUrl('edit-username'),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', "Votre nom d'utilisateur a été changé");

            return $this->redirectToRoute('user-account');
        } else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "Votre nom d'utilisateur doit faire entre 3 et 30 caractères");

            return $this->redirectToRoute('user-account');
        }

        return $this->render('security/_edit_username_form.html.twig', [
            'usernameForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/espace-utilisateur/compte/editer-email", name="edit-email")
     */
    public function editEmail(Request $request, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        $actualPassword = $user->getPassword();
        $form = $this->createForm(EditEmailFormType::class, $user, [
            'action' => $this->generateUrl('edit-email'),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $enteredPassword = $form['confirm_password']->getData();

            if (password_verify($enteredPassword, $actualPassword)) {
                $manager->persist($user);
                $manager->flush();

                $this->addFlash('success', "Votre e-mail a bien été changé");

                return $this->redirectToRoute('user-account');
            } else {
                $this->addFlash('danger', "Ce n'est pas le bon mot de passe");

                return $this->redirectToRoute('user-account');
            }
        } else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "Veuillez saisir un email valide");

            return $this->redirectToRoute('user-account');
        }

        return $this->render('security/_edit_email_form.html.twig', [
            'emailForm' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/espace-utilisateur/compte/editer-mot-de-passe", name="edit-password")
     */
    public function editPassword(Request $request, EntityManagerInterface $manager,UserPasswordHasherInterface $hasher): Response
    {
        $user = $this->getUser();
        $actualPassword = $user->getPassword();
        $form = $this->createForm(EditPasswordFormType::class, $user, [
            'action' => $this->generateUrl('edit-password'),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $oldPassword = $form['old_password']->getData();

            if (password_verify($oldPassword, $actualPassword)) {
                $hashedNewPassword = $hasher->hashPassword($user, $user->getPassword());
                $user->setPassword($hashedNewPassword);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash('success', "Votre mot de passe a bien été changé");

                return $this->redirectToRoute('user-account');
            } else {
                $this->addFlash('danger', "Votre ancien mot de passe ne correspond pas à l'actuel");

                return $this->redirectToRoute('user-account');
            }
        } else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "Les mots de passe ne correspondent pas ou ils doivent faire entre 8 et 30 caractères et contenir une majuscule, une minuscule et un chiffre");

            return $this->redirectToRoute('user-account');
        }

        return $this->render('security/_edit_password_form.html.twig', [
            'passwordForm' => $form->createView()
        ]);
    }

        /**
     * @Route("/espace-utilisateur/compte/editer-photo-profil", name="edit-profil-picture")
     */
    public function editProfilPicture(Request $request, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(EditProfilPictureFormType::class, $user, [
            'action' => $this->generateUrl('edit-profil-picture'),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', "Votre photo de profil a été ajoutée");

            return $this->redirectToRoute('user-account');
        } else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "ça marche pas !");

            return $this->redirectToRoute('user-account');
        }

        return $this->render('security/_edit_profil_picture_form.html.twig', [
            'ProfilPictureForm' => $form->createView()
        ]);
    }
}        
