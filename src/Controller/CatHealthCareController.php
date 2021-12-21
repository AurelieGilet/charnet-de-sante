<?php

namespace App\Controller;

use App\Entity\Cat;
use App\Entity\HealthCare;
use App\Repository\CatRepository;
use App\Form\CatHealthCareFormType;
use App\Repository\HealthCareRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CatHealthCareController extends AbstractController
{
    // This method is used in the different routes with ID in the URL to secure them and prevent users to access routes they don't have permission to.
    // There is 2 kind of users : the normal users and the guests. Users are limited to the pages concerning their own cats. Guests are limited to the pages concerning THE cat the users gave them access to. 
    private function isRouteSecure($className, $user, $cat) {
        if ($className == "App\Entity\User") {
            if ($cat->getOwner() != $user) {
                $this->addFlash('danger', "Vous n'avez pas accès à cette fiche");
                return $this->redirectToRoute('cat-list');
            }
        } elseif ($className == "App\Entity\Guest") {
            if ($cat->getId() != $user->getGuestCode()->getCat()->getId()) {
                $this->addFlash('danger', "Vous n'avez pas accès à cette fiche");
                return $this->redirectToRoute('homepage');
            }
        } 

        return null;
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/soins", name="cat-healthCare")
     * @Route("/espace-veterinaire/chat/{id}/soins", name="veterinary-cat-healthCare")
     */
    public function catHealthCare(Cat $cat): Response
    {
        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        return $this->render('cat-interface/cat-healthcare/cat_healthCare.html.twig', [
            'controller_name' => 'CatHealthCareController',
            'cat' => $cat
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/soins/vaccin", name="cat-vaccine")
     * @Route("/espace-veterinaire/chat/{id}/soins/vaccin", name="veterinary-cat-vaccine")
     */
    public function catVaccine(Request $request, Cat $cat, HealthCareRepository $healthCareRepository, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }

        $healthCares = $healthCareRepository->findCatVaccines($cat);

        $paginatedHealthCares = $paginator->paginate(
            $healthCares,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('cat-interface/cat-healthcare/cat_healthcare_vaccine.html.twig', [
            'controller_name' => 'CatHealthCareController',
            'cat' => $cat,
            'paginatedHealthCares' => $paginatedHealthCares,
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/soins/vermifuge", name="cat-dewormer")
     * @Route("/espace-veterinaire/chat/{id}/soins/vermifuge", name="veterinary-cat-dewormer")
     */
    public function catDewormer(Request $request, Cat $cat, HealthCareRepository $healthCareRepository, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }

        $healthCares = $healthCareRepository->findCatDewormers($cat);

        $paginatedHealthCares = $paginator->paginate(
            $healthCares,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('cat-interface/cat-healthcare/cat_healthcare_dewormer.html.twig', [
            'controller_name' => 'CatHealthCareController',
            'cat' => $cat,
            'paginatedHealthCares' => $paginatedHealthCares,
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/soins/antiparasitaire", name="cat-antiparasite")
     * @Route("/espace-veterinaire/chat/{id}/soins/antiparasitaire", name="veterinary-cat-antiparasite")
     */
    public function catAntiparasite(Request $request, Cat $cat, HealthCareRepository $healthCareRepository, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }

        $healthCares = $healthCareRepository->findCatAntiparasites($cat);

        $paginatedHealthCares = $paginator->paginate(
            $healthCares,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('cat-interface/cat-healthcare/cat_healthcare_antiparasite.html.twig', [
            'controller_name' => 'CatHealthCareController',
            'cat' => $cat,
            'paginatedHealthCares' => $paginatedHealthCares,
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/soins/traitements", name="cat-treatment")
     * @Route("/espace-veterinaire/chat/{id}/soins/traitements", name="veterinary-cat-treatment")
     */
    public function catTreatments(Request $request, Cat $cat, HealthCareRepository $healthCareRepository, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }

        // We create 2 sets of data : those that are finished (with end date) and those that are ongoing.
        $healthCares = $healthCareRepository->findCatTreatments($cat);

        $paginatedHealthCares = $paginator->paginate(
            $healthCares,
            $request->query->getInt('page', 1),
            5,
            [
                'pageParameterName' => 'page',
                'sortFieldParameterName' => 'sort1',
                'sortDirectionParameterName' => 'direction1',
            ]
        );

        $currentDate = new DateTime();

        $currentHealthCares = $healthCareRepository->findCatCurrentTreatments($cat, $currentDate);

        $paginatedCurrentHealthCares = $paginator->paginate(
            $currentHealthCares,
            $request->query->getInt('page2', 1),
            5,
            [
                'pageParameterName' => 'page2',
                'sortFieldParameterName' => 'sort2',
                'sortDirectionParameterName' => 'direction2',
            ]
        );

        return $this->render('cat-interface/cat-healthcare/cat_healthcare_treatment.html.twig', [
            'controller_name' => 'CatHealthCareController',
            'cat' => $cat,
            'paginatedHealthCares' => $paginatedHealthCares,
            'paginatedCurrentHealthCares' => $paginatedCurrentHealthCares,
            'currentHealthCares' => $currentHealthCares,
        ]);
    }
    
    /**
     * @Route("/espace-utilisateur/chat/{id}/soins/detartrage", name="cat-descaling")
     * @Route("/espace-veterinaire/chat/{id}/soins/detartrage", name="veterinary-cat-descaling")
     */
    public function catDescaling(Request $request, Cat $cat, HealthCareRepository $healthCareRepository, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }

        $healthCares = $healthCareRepository->findCatDescaling($cat);

        $paginatedHealthCares = $paginator->paginate(
            $healthCares,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('cat-interface/cat-healthcare/cat_healthcare_descaling.html.twig', [
            'controller_name' => 'CatHealthCareController',
            'cat' => $cat,
            'paginatedHealthCares' => $paginatedHealthCares,
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/soins/vaccin/ajouter", name="add-vaccine")
     */
    public function addVaccine(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, Cat $cat): Response 
    {
        $cat = $catRepository->findOneBy(['id' => $cat]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }

        $healthCare = new HealthCare;

        $form = $this->createForm(CatHealthCareFormType::class, $healthCare, [
            'action' => $this->generateUrl('add-vaccine', ['id' => $cat->getId(),
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getVaccine() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir une date et au moins un type de vaccin.");

                return $this->redirectToRoute('cat-vaccine', ['id' => $cat->getId() ]);
            }

            $healthCare->setCat($cat);

            $manager->persist($healthCare);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été ajoutée");

            return $this->redirectToRoute('cat-vaccine', ['id' => $cat->getId() ]);

        } else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir une date et au moins un type de vaccin.");

            return $this->redirectToRoute('cat-vaccine', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-healthcare/_add_edit_cat_vaccine.html.twig', [
            'controller_name' => 'CatHealthCareController',
            'healthCareForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/soins/vermifuge/ajouter", name="add-dewormer")
     */
    public function addDewormer(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, Cat $cat): Response 
    {
        $cat = $catRepository->findOneBy(['id' => $cat]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }

        $healthCare = new HealthCare;

        $form = $this->createForm(CatHealthCareFormType::class, $healthCare, [
            'action' => $this->generateUrl('add-dewormer', ['id' => $cat->getId(),
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $healthCare->setCat($cat);
            $healthCare->setDewormer(true);

            $manager->persist($healthCare);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été ajoutée");

            return $this->redirectToRoute('cat-dewormer', ['id' => $cat->getId() ]);

        } else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir une date.");

            return $this->redirectToRoute('cat-dewormer', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-healthcare/_add_edit_cat_dewormer.html.twig', [
            'controller_name' => 'CatHealthCareController',
            'healthCareForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/soins/antiparasitaire/ajouter", name="add-antiparasite")
     */
    public function addAntiparasite(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, Cat $cat): Response 
    {
        $cat = $catRepository->findOneBy(['id' => $cat]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }

        $healthCare = new HealthCare;

        $form = $this->createForm(CatHealthCareFormType::class, $healthCare, [
            'action' => $this->generateUrl('add-antiparasite', ['id' => $cat->getId(),
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getParasite() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir une date et un type de parasite.");

                return $this->redirectToRoute('cat-antiparasite', ['id' => $cat->getId() ]);
            }

            $healthCare->setCat($cat);

            $manager->persist($healthCare);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été ajoutée");

            return $this->redirectToRoute('cat-antiparasite', ['id' => $cat->getId() ]);

        } else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir une date et un type de parasite.");

            return $this->redirectToRoute('cat-antiparasite', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-healthcare/_add_edit_cat_antiparasite.html.twig', [
            'controller_name' => 'CatHealthCareController',
            'healthCareForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/soins/traitements/ajouter", name="add-treatment")
     */
    public function addTreatment(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, Cat $cat): Response 
    {        
        $cat = $catRepository->findOneBy(['id' => $cat]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }

        $healthCare = new HealthCare;

        $form = $this->createForm(CatHealthCareFormType::class, $healthCare, [
            'action' => $this->generateUrl('add-treatment', ['id' => $cat->getId(),
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getTreatment() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir une date de début et le motif du traitement.");

                return $this->redirectToRoute('cat-treatment', ['id' => $cat->getId() ]);
            }

            $healthCare->setCat($cat);

            $manager->persist($healthCare);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été ajoutée");

            return $this->redirectToRoute('cat-treatment', ['id' => $cat->getId() ]);

        } else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir une date de début et le motif du traitement.");

            return $this->redirectToRoute('cat-treatment', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-healthcare/_add_edit_cat_treatment.html.twig', [
            'controller_name' => 'CatHealthCareController',
            'healthCareForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/soins/detartrage/ajouter", name="add-descaling")
     */
    public function addDescaling(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, Cat $cat): Response 
    {
        $cat = $catRepository->findOneBy(['id' => $cat]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $healthCare = new HealthCare;

        $form = $this->createForm(CatHealthCareFormType::class, $healthCare, [
            'action' => $this->generateUrl('add-descaling', ['id' => $cat->getId(),
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getDescaling() == null) {
                $healthCare->setDescaling('R.A.S');
            }

            $healthCare->setCat($cat);

            $manager->persist($healthCare);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été ajoutée");

            return $this->redirectToRoute('cat-descaling', ['id' => $cat->getId() ]);

        } else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir une date.");

            return $this->redirectToRoute('cat-descaling', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-healthcare/_add_edit_cat_descaling.html.twig', [
            'controller_name' => 'CatHealthCareController',
            'healthCareForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{catId}/soins/vaccin/{healthCareId}/editer", name="edit-vaccine")
     */
    public function editVaccine(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, HealthCareRepository $healthCareRepository, Cat $cat = null, HealthCare $healthCare = null): Response 
    {       
        $catId = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catId]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }

        $healthCareId = $request->attributes->get('healthCareId');
        $healthCare = $healthCareRepository->findOneBy(['id' => $healthCareId]);

        $form = $this->createForm(CatHealthCareFormType::class, $healthCare, [
            'action' => $this->generateUrl('edit-vaccine', ['catId' => $cat->getId(), 'healthCareId' => $healthCare->getId()
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getVaccine() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été modifiée. Vous devez saisir une date et au moins un type de vaccin.");

                return $this->redirectToRoute('cat-vaccine', ['id' => $cat->getId() ]);
            }

            $manager->persist($healthCare);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été modifiée");

            return $this->redirectToRoute('cat-vaccine', ['id' => $cat->getId() ]);

        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été modifiée. Vous devez saisir une date et au moins un type de vaccin.");

            return $this->redirectToRoute('cat-vaccine', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-healthCare/_add_edit_cat_vaccine.html.twig', [
            'controller_name' => 'CatHealthCareController',
            'healthCareForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{catId}/soins/vermifuge/{healthCareId}/editer", name="edit-dewormer")
     */
    public function editDewormer(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, HealthCareRepository $healthCareRepository, Cat $cat = null, HealthCare $healthCare = null): Response 
    {        
        $catId = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catId]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }

        $healthCareId = $request->attributes->get('healthCareId');
        $healthCare = $healthCareRepository->findOneBy(['id' => $healthCareId]);

        $form = $this->createForm(CatHealthCareFormType::class, $healthCare, [
            'action' => $this->generateUrl('edit-dewormer', ['catId' => $cat->getId(), 'healthCareId' => $healthCare->getId()
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($healthCare);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été modifiée");

            return $this->redirectToRoute('cat-dewormer', ['id' => $cat->getId() ]);

        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été modifiée. Vous devez saisir une date.");

            return $this->redirectToRoute('cat-dewormer', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-healthCare/_add_edit_cat_dewormer.html.twig', [
            'controller_name' => 'CatHealthCareController',
            'healthCareForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{catId}/soins/antiparasitaire/{healthCareId}/editer", name="edit-antiparasite")
     */
    public function editAntiparasite(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, HealthCareRepository $healthCareRepository, Cat $cat = null, HealthCare $healthCare = null): Response 
    {        
        $catId = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catId]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }

        $healthCareId = $request->attributes->get('healthCareId');
        $healthCare = $healthCareRepository->findOneBy(['id' => $healthCareId]);

        $form = $this->createForm(CatHealthCareFormType::class, $healthCare, [
            'action' => $this->generateUrl('edit-antiparasite', ['catId' => $cat->getId(), 'healthCareId' => $healthCare->getId()
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getParasite() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été modifiée. Vous devez saisir une date et un type de parasite.");

                return $this->redirectToRoute('cat-antiparasite', ['id' => $cat->getId() ]);
            }

            $manager->persist($healthCare);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été modifiée");

            return $this->redirectToRoute('cat-antiparasite', ['id' => $cat->getId() ]);

        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été modifiée. Vous devez saisir une date et un type de parasite.");

            return $this->redirectToRoute('cat-antiparasite', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-healthCare/_add_edit_cat_antiparasite.html.twig', [
            'controller_name' => 'CatHealthCareController',
            'healthCareForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{catId}/soins/traitements/{healthCareId}/editer", name="edit-treatment")
     */
    public function editTreatment(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, HealthCareRepository $healthCareRepository, Cat $cat = null, HealthCare $healthCare = null): Response 
    {        
        $catId = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catId]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }

        $healthCareId = $request->attributes->get('healthCareId');
        $healthCare = $healthCareRepository->findOneBy(['id' => $healthCareId]);

        $form = $this->createForm(CatHealthCareFormType::class, $healthCare, [
            'action' => $this->generateUrl('edit-treatment', ['catId' => $cat->getId(), 'healthCareId' => $healthCare->getId()
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getTreatment() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été modifiée. Vous devez saisir une date de début et le motif du traitement.");

                return $this->redirectToRoute('cat-treatment', ['id' => $cat->getId() ]);
            }

            $manager->persist($healthCare);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été modifiée");

            return $this->redirectToRoute('cat-treatment', ['id' => $cat->getId() ]);

        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été modifiée. Vous devez saisir une date de début et le motif du traitement.");

            return $this->redirectToRoute('cat-treatment', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-healthCare/_add_edit_cat_treatment.html.twig', [
            'controller_name' => 'CatHealthCareController',
            'healthCareForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{catId}/soins/detartrage/{healthCareId}/editer", name="edit-descaling")
     */
    public function editDescaling(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, HealthCareRepository $healthCareRepository, Cat $cat = null, HealthCare $healthCare = null): Response 
    {        
        $catId = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catId]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }

        $healthCareId = $request->attributes->get('healthCareId');
        $healthCare = $healthCareRepository->findOneBy(['id' => $healthCareId]);

        $form = $this->createForm(CatHealthCareFormType::class, $healthCare, [
            'action' => $this->generateUrl('edit-descaling', ['catId' => $cat->getId(), 'healthCareId' => $healthCare->getId()
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getDescaling() == null) {
                $healthCare->setDescaling('R.A.S');
            }

            $manager->persist($healthCare);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été modifiée");

            return $this->redirectToRoute('cat-descaling', ['id' => $cat->getId() ]);

        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été modifiée. Vous devez saisir une date.");

            return $this->redirectToRoute('cat-descaling', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-healthCare/_add_edit_cat_descaling.html.twig', [
            'controller_name' => 'CatHealthCareController',
            'healthCareForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{catId}/soins/vaccin/{healthCareId}/supprimer", name="delete-healthCare")
     * 
     * @Route("/espace-utilisateur/chat/{catId}/soins/vermifuge/{healthCareId}/supprimer", name="delete-healthCare")
     * 
     * @Route("/espace-utilisateur/chat/{catId}/soins/antiparasitaire/{healthCareId}/supprimer", name="delete-healthCare")
     * 
     * @Route("/espace-utilisateur/chat/{catId}/soins/traitements/{healthCareId}/supprimer", name="delete-healthCare")
     * 
     * @Route("/espace-utilisateur/chat/{catId}/soins/detartrage/{healthCareId}/supprimer", name="delete-healthCare")
     */
    public function deleteHealthCare(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, HealthCareRepository $healthCareRepository, Cat $cat = null, HealthCare $healthCare = null): Response 
    {               
        $catId = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catId]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }

        $healthCareId = $request->attributes->get('healthCareId');
        $healthCare = $healthCareRepository->findOneBy(['id' => $healthCareId]);

        // To avoir having to create a delete method for each kind of healthCare data, we create variables that will store the corresponding data. 
        // The one we are deleting is the only variable that is not empty. We then use this variable to determine the redirection route.
        $vaccine = $healthCare->getVaccine();
        $dewormer = $healthCare->getDewormer();
        $parasite = $healthCare->getParasite();
        $treatment = $healthCare->getTreatment();
        $descaling = $healthCare->getDescaling();

        $form = $this->createForm(CatHealthCareFormType::class, $healthCare, [
            'action' => $this->generateUrl('delete-healthCare', ['catId' => $cat->getId(), 'healthCareId' => $healthCare->getId()
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->remove($healthCare);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été supprimée");

            // Here we check each variable to find the one that is not empty and will determine the redirection route in case of success or failure.
            if ($vaccine != null) {
                return $this->redirectToRoute('cat-vaccine', ['id' => $cat->getId() ]);
            } else if ($dewormer != null) {
                return $this->redirectToRoute('cat-dewormer', ['id' => $cat->getId() ]);
            } else if ($parasite != null) {
                return $this->redirectToRoute('cat-antiparasite', ['id' => $cat->getId() ]);
            } else if ($treatment != null) {
                return $this->redirectToRoute('cat-treatment', ['id' => $cat->getId() ]);
            } else if ($descaling != null) {
                return $this->redirectToRoute('cat-descaling', ['id' => $cat->getId() ]);
            }  else {
                return $this->redirectToRoute('cat-healthCare', ['id' => $cat->getId() ]);
            } 
        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée' n'a pas été supprimée");

            if ($vaccine  != null) {
                return $this->redirectToRoute('cat-vaccine', ['id' => $cat->getId() ]);
            } else if ($dewormer != null) {
                return $this->redirectToRoute('cat-dewormer', ['id' => $cat->getId() ]);
            } else if ($parasite != null) {
                return $this->redirectToRoute('cat-antiparasite', ['id' => $cat->getId() ]);
            } else if ($treatment != null) {
                return $this->redirectToRoute('cat-treatment', ['id' => $cat->getId() ]);
            } else if ($descaling != null) {
                return $this->redirectToRoute('cat-descaling', ['id' => $cat->getId() ]);
            }  else {
                return $this->redirectToRoute('cat-healthCare', ['id' => $cat->getId() ]);
            }
        }

        return $this->render('cat-interface/cat-healthcare/_delete_cat_healthcare.html.twig', [
            'controller_name' => 'CatHealthCareController',
            'healthCareForm' => $form->createView(),
            'catId' => $cat->getId(),
            'healthCareId' => $healthCare->getId(),
        ]);
    }
}
