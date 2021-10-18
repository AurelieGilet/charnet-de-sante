<?php

namespace App\Controller;

use App\Form\DeleteUserFormType;
use App\Repository\CatRepository;
use App\Repository\UserRepository;
use App\Repository\HealthRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/espace-administrateur", name="admin-interface")
     */
    public function adminInterface(): Response
    {
        return $this->render('admin-interface/admin_interface.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/espace-administrateur/gestion-utilisateurs", name="admin-users")
     */
    public function adminUsers(Request $request, UserRepository $userRepository, PaginatorInterface $paginator): Response
    {
        $users = $userRepository->findAll();

        $paginatedUsers = $paginator->paginate(
            $users,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('admin-interface/admin_users.html.twig', [
            'controller_name' => 'AdminController',
            'paginatedUsers' => $paginatedUsers,
        ]);
    }

    /**
     * @Route("/espace-administrateur/gestion-utilisateurs/{userId}/supprimer-utilisateur", name="admin-delete-user")
     */
    public function adminDeleteUser(Request $request, EntityManagerInterface $manager, UserRepository $userRepository, CatRepository $catRepository, HealthRepository $healthRepository): Response
    {
        $admin = $this->getUser();
        $adminPassword = $admin->getPassword();

        $userId = $request->attributes->get('userId');
        $user = $userRepository->findOneBy(['id' => $userId]);
        $username = $user->getUsername();
        dump($username);
        $userPicture = $user->getPicture();

        $userCats = $catRepository->findBy(['owner' => $user]);
        $catsPictures = [];
        $catsDocuments = [];

        for ($i = 0; $i < count($userCats); $i++) { 
            array_push($catsPictures, $userCats[$i]->getPicture());
        }

        for ($i = 0; $i < count($userCats); $i++) { 
            array_push($catsDocuments, $healthRepository->findCatFilenames($userCats[$i]));
        }

        $form = $this->createForm(DeleteUserFormType::class, $user, [
            'action' => $this->generateUrl('admin-delete-user', ['userId' => $user->getId() ]),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $enteredPassword = $form['password']->getData();

            if (password_verify($enteredPassword, $adminPassword)) {
                $manager->remove($user);
                $manager->flush($user);

                $filesystem = new Filesystem();
                $filesystem->remove($this->getParameter('images_directory') . '/' . $userPicture);
                for ($i = 0; $i < count($catsPictures); $i++) { 
                    $filesystem->remove($this->getParameter('images_directory') . '/' . $catsPictures[$i]);
                }
                for ($i = 0; $i < count($catsDocuments); $i++) {
                    for ($j = 0; $j < count($catsDocuments[$i]); $j++) { 
                        $filesystem->remove($this->getParameter('files_directory') . '/' . $catsDocuments[$i][$j]['document']);
                    } 
                }

                $this->addFlash('success', "L'utilisateur ". $username . " a bien été supprimé.");

                return $this->redirectToRoute('admin-users');

            } else {
                $this->addFlash('danger', "L'utilisateur ". $username . " n'a pas été supprimé. Le mot de passe ne correspond pas.");

                return $this->redirectToRoute('admin-users');
            }
        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'utilisateur ". $username . " n'a pas été supprimé. Vous devez confirmez par mot de passe.");

            return $this->redirectToRoute('admin-users');
        }

        return $this->render('admin-interface/_delete_user_form.html.twig', [
            'controller_name' => 'AdminController',
            'deleteUserForm' => $form->createView()
        ]);
    }
}
