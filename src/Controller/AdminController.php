<?php

namespace App\Controller;

use App\Entity\FAQ;
use App\Form\AdminFAQFormType;
use App\Form\SearchFAQFormType;
use App\Form\AdminUsersFormType;
use App\Form\DeleteUserFormType;
use App\Form\SearchUserFormType;
use App\Repository\CatRepository;
use App\Repository\FAQRepository;
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

        $form = $this->createForm(SearchUserFormType::class);

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $criteria = $form->getData();

            $users = $userRepository->findUserBySearch($criteria);

            $paginatedUsers = $paginator->paginate(
                $users,
                $request->query->getInt('page', 1),
                5
            );
        }

        return $this->render('admin-interface/admin_users.html.twig', [
            'controller_name' => 'AdminController',
            'searchForm' => $form->createView(),
            'paginatedUsers' => $paginatedUsers,
        ]);
    }

    /**
     * @Route("/espace-administrateur/gestion-faq", name="admin-faq")
     */
    public function adminFAQ(Request $request, FAQRepository $faqRepository, PaginatorInterface $paginator): Response
    {
        $faqs = $faqRepository->findBy(array(),
                                       array('id'=>'DESC') 
                                );

        $paginatedFAQ = $paginator->paginate(
            $faqs,
            $request->query->getInt('page', 1),
            10
        );

        $form = $this->createForm(SearchFAQFormType::class);

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $criteria = $form->getData();
            $criteria = explode(" ", $criteria['question']);

            $faqs = $faqRepository->findFAQBySearch($criteria);

            $paginatedFAQ = $paginator->paginate(
                $faqs,
                $request->query->getInt('page', 1),
                10
            );
        }

        return $this->render('admin-interface/admin_faq.html.twig', [
            'controller_name' => 'AdminController',
            'searchForm' => $form->createView(),
            'paginatedFAQ' => $paginatedFAQ,
        ]);
    }

    /**
     * @Route("/espace-administrateur/gestion-faq/ajouter", name="admin-add-faq")
     */
    public function adminAddFAQ(Request $request, EntityManagerInterface $manager): Response
    {
        $faq = new FAQ();

        $form = $this->createForm(AdminFAQFormType::class, $faq, [
            'action' => $this->generateUrl('admin-add-faq'
            ),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getQuestion() == null || $form->getData()->getAnswer() == null) {
                $this->addFlash('danger', "La question n'a pas été ajoutée. Vous devez remplir tous les champs.");

                return $this->redirectToRoute('admin-faq');
            }

            $manager->persist($faq);
            $manager->flush();

            $this->addFlash('success', "La question a été ajoutée");

            return $this->redirectToRoute('admin-faq');
        } else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "La question n'a pas été ajoutée. Vous devez remplir tous les champs.");

            return $this->redirectToRoute('admin-faq');
        }

        return $this->render('admin-interface/_add_edit_faq_form.html.twig', [
            'controller_name' => 'AdminController',
            'adminFAQForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/espace-administrateur/gestion-utilisateurs/{userId}/editer-role", name="admin-edit-user-role")
     */
    public function adminEditUserRole(Request $request, EntityManagerInterface $manager, UserRepository $userRepository): Response
    {
        $userId = $request->attributes->get('userId');
        $user = $userRepository->findOneBy(['id' => $userId]);
        $username = $user->getUsername();

        $form = $this->createForm(AdminUsersFormType::class, $user, [
            'action' => $this->generateUrl('admin-edit-user-role', ['userId' => $user->getId() ]),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($user);
            $manager->flush($user);

            $this->addFlash('success', "Le rôle de l'utilisateur ". $username . " a bien été modifié.");

            return $this->redirectToRoute('admin-users');

        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "Le rôle de l'utilisateur ". $username . " n'a pas été modifié. Vous devez choisir un rôle.");

            return $this->redirectToRoute('admin-users');
        }

        return $this->render('admin-interface/_edit_roles_form.html.twig', [
            'controller_name' => 'AdminController',
            'adminUsersForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/espace-administrateur/gestion-faq/{faqId}/editer", name="admin-edit-faq")
     */
    public function adminEditFAQ(Request $request, EntityManagerInterface $manager, FAQRepository $faqRepository): Response
    {
        $faqId = $request->attributes->get('faqId');
        $faq = $faqRepository->findOneBy(['id' => $faqId]);

        $form = $this->createForm(AdminFAQFormType::class, $faq, [
            'action' => $this->generateUrl('admin-edit-faq', ['faqId' => $faq->getId()
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getQuestion() == null || $form->getData()->getAnswer() == null) {
                $this->addFlash('danger', "La question n'a pas été modifiée. Vous devez remplir tous les champs.");

                return $this->redirectToRoute('admin-faq');
            }

            $manager->persist($faq);
            $manager->flush();

            $this->addFlash('success', "La question a été modifiée");

            return $this->redirectToRoute('admin-faq');
        } else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "La question n'a pas été modifiée. Vous devez remplir tous les champs.");

            return $this->redirectToRoute('admin-faq');
        }

        return $this->render('admin-interface/_add_edit_faq_form.html.twig', [
            'controller_name' => 'AdminController',
            'adminFAQForm' => $form->createView(),
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
        $userPicture = $user->getPicture();

        $guest = $user->getGuest();
        $guestCode = $user->getGuestCode();

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
                $manager->remove($guest);
                $manager->remove($guestCode);
                $manager->flush();

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
            $this->addFlash('danger', "L'utilisateur ". $username . " n'a pas été supprimé. Vous devez confirmer par mot de passe.");

            return $this->redirectToRoute('admin-users');
        }

        return $this->render('admin-interface/_delete_user_form.html.twig', [
            'controller_name' => 'AdminController',
            'deleteUserForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/espace-administrateur/gestion-faq/{faqId}/supprimer-faq", name="admin-delete-faq")
     */
    public function adminDeleteFAQ(Request $request, EntityManagerInterface $manager, FAQRepository $faqRepository): Response
    {
        $faqId = $request->attributes->get('faqId');
        $faq = $faqRepository->findOneBy(['id' => $faqId]);

        $form = $this->createForm(AdminFAQFormType::class, $faq, [
            'action' => $this->generateUrl('admin-delete-faq', ['faqId' => $faq->getId()
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->remove($faq);
            $manager->flush();

            $this->addFlash('success', "La question a été supprimée");

            return $this->redirectToRoute('admin-faq');
        } else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "La question n'a pas été supprimée.");

            return $this->redirectToRoute('admin-faq');
        }

        return $this->render('admin-interface/_delete_faq_form.html.twig', [
            'controller_name' => 'AdminController',
            'adminFAQForm' => $form->createView()
        ]);
    }
}
