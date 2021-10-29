<?php

namespace App\Controller;

use App\Entity\Guest;
use App\Entity\User;
use App\Form\EditEmailFormType;
use App\Form\DeleteUserFormType;
use App\Form\EditPictureFormType;
use App\Repository\CatRepository;
use App\Form\EditPasswordFormType;
use App\Form\EditUsernameFormType;
use App\Form\RegistrationFormType;
use App\Form\DeletePictureFormType;
use App\Repository\HealthRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
            'controller_name' => 'SecurityController',
            'last_username' => $lastUsername, 
            'error' => $error,
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
        $guest = new Guest;

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            
            $hash = $hasher->hashPassword($user, $user->getPassword());
            $userRoles = ["ROLE_USER"];
            $guestRoles = ["ROLE_GUEST"];

            $user->setPassword($hash);
            $user->setRoles($userRoles);

            $manager->persist($user);
            $manager->flush();

            $guest->setUser($user);
            $guest->setPassword('000000000');
            $guest->setUsername('user-'.$user->getId().'-guest');
            $guest->setRoles($guestRoles);
            
            $manager->persist($guest);
            $manager->flush();

            $this->addFlash('success', "Votre compte a bien été créé");

            return $this->redirectToRoute('login');
        }

        return $this->render('security/registration.html.twig', [
            'controller_name' => 'SecurityController',
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/espace-utilisateur/compte/supprimer", name="delete-user")
     */
    public function deleteUser(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, HealthRepository $healthRepository): Response
    {
        $user = $this->getUser();
        $userPassword = $user->getPassword();
        $userPicture = $user->getPicture();

        $cats = $catRepository->findBy(['owner' => $user]);
        $catsPictures = [];
        $documents = [];

        for ($i = 0; $i < count($cats); $i++) { 
            array_push($catsPictures, $cats[$i]->getPicture());
        }

        for ($i = 0; $i < count($cats); $i++) { 
            array_push($documents, $healthRepository->findCatFilenames($cats[$i]));
        }

        $form = $this->createForm(DeleteUserFormType::class, $user, [
            'action' => $this->generateUrl('delete-user'),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $enteredPassword = $form['password']->getData();

            if (password_verify($enteredPassword, $userPassword)) {

                $session = $this->get('session');
                $session = new Session();
                $session->invalidate();

                $manager->remove($user);
                $manager->flush($user);

                $filesystem = new Filesystem();
                $filesystem->remove($this->getParameter('images_directory') . '/' . $userPicture);
                for ($i = 0; $i < count($catsPictures); $i++) { 
                    $filesystem->remove($this->getParameter('images_directory') . '/' . $catsPictures[$i]);
                }
                for ($i = 0; $i < count($documents); $i++) {
                    for ($j = 0; $j < count($documents[$i]); $j++) { 
                        $filesystem->remove($this->getParameter('files_directory') . '/' . $documents[$i][$j]['document']);
                    } 
                }

                return $this->redirectToRoute('logout');

            } else {
                $this->addFlash('danger', "Votre compte n'a pas été supprimé. Le mot de passe ne correspond pas.");

                return $this->redirectToRoute('user-account');
            }
        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "Votre compte n'a pas été supprimé. Vous devez confirmez par mot de passe.");

            return $this->redirectToRoute('user-account');
        }

        return $this->render('security/_delete_user.html.twig', [
            'controller_name' => 'SecurityController',
            'deleteUserForm' => $form->createView()
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
            'controller_name' => 'SecurityController',
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
            'controller_name' => 'SecurityController',
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
            'controller_name' => 'SecurityController',
            'passwordForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/espace-utilisateur/compte/editer-photo", name="edit-picture", methods={"POST"}, options={"expose"=true})
     */
    public function editPicture(Request $request, EntityManagerInterface $manager, SluggerInterface $slugger): Response
    {
        $user = $this->getUser();
        $oldPicture = $user->getPicture();

        $form = $this->createForm(EditPictureFormType::class, $user);
        $form->handleRequest($request);

        if ($request->isXmlHttpRequest()) {
            
            $file = $_FILES['file'];
            $file = new UploadedFile($file['tmp_name'], $file['name'], $file['type']);

            if (filesize($file) <= 2000000) {
                if ($oldPicture) {
                    $filesystem = new Filesystem();
                    $filesystem->remove($this->getParameter('images_directory') . '/' . $oldPicture);
                }

                $filename = $slugger->slug($user->getId()) . '-' . uniqid() . '.' . $file->guessExtension();

                $file->move(
                    $this->getParameter('images_directory'),
                    $filename
                );

                $user->setPicture($filename);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash('success', "Votre photo de profil a été ajoutée");
                
                return new JsonResponse();
                
            } else {
                $this->addFlash('danger', "Votre photo de profil n'a pas été modifiée. L'image doit faire moins de 2 Mo.");

                return new JsonResponse(415);
            }
        } 

        return $this->render('security/_edit_picture_form.html.twig', [
            'controller_name' => 'SecurityController',
            'pictureForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/espace-utilisateur/compte/supprimer-photo", name="delete-picture")
     */
    public function deletePicture(Request $request, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();

        $picture = $user->getPicture();

        $form = $this->createForm(DeletePictureFormType::class, $user, [
            'action' => $this->generateUrl('delete-picture'),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

                $user->setPicture(null);

                $manager->persist($user);
                $manager->flush($user);

                $filesystem = new Filesystem();
                $filesystem->remove($this->getParameter('images_directory') . '/' . $picture);

                return $this->redirectToRoute('user-account');

        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'avatar n'a pas été supprimé, veuillez confirmer votre choix");

            return $this->redirectToRoute('user-account');
        }

        return $this->render('security/_delete_picture.html.twig', [
            'controller_name' => 'SecurityController',
            'deletePictureForm' => $form->createView(),
        ]);
    }
}        
