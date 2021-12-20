<?php

namespace App\Controller;

use App\Entity\Cat;
use App\Entity\PetCare;
use App\Form\CatPetCareFormType;
use App\Repository\CatRepository;
use App\Repository\PetCareRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CatPetCareController extends AbstractController
{
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
     * @Route("/espace-utilisateur/chat/{id}/entretien", name="cat-petCare")
     * @Route("/espace-veterinaire/chat/{id}/entretien", name="veterinary-cat-petCare")
     */
    public function catPetcare(Cat $cat): Response
    {
        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        return $this->render('cat-interface/cat-petcare/cat_petcare.html.twig', [
            'controller_name' => 'CatPetCareController',
            'cat' => $cat
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/entretien/alimentation", name="cat-feeding")
     * @Route("/espace-veterinaire/chat/{id}/entretien/alimentation", name="veterinary-cat-feeding")
     */
    public function catFeeding(Request $request, Cat $cat, PetCareRepository $petCareRepository, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $petCares = $petCareRepository->findCatFeedings($cat);

        $paginatedPetCares = $paginator->paginate(
            $petCares,
            $request->query->getInt('page', 1),
            5,
            [
                'pageParameterName' => 'page',
                'sortFieldParameterName' => 'sort1',
                'sortDirectionParameterName' => 'direction1',
            ]
        );

        $currentPetCares = $petCareRepository->findCatCurrentFeedings($cat);

        $paginatedCurrentPetCares = $paginator->paginate(
            $currentPetCares,
            $request->query->getInt('page2', 1),
            5,
            [
                'pageParameterName' => 'page2',
                'sortFieldParameterName' => 'sort2',
                'sortDirectionParameterName' => 'direction2',
            ]
        );

        return $this->render('cat-interface/cat-petcare/cat_petcare_feeding.html.twig', [
            'controller_name' => 'CatPetCareController',
            'cat' => $cat,
            'paginatedPetCares' => $paginatedPetCares,
            'paginatedCurrentPetCares' => $paginatedCurrentPetCares,
            'currentPetCares' => $currentPetCares,
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/entretien/toilettage", name="cat-grooming")
     * @Route("/espace-veterinaire/chat/{id}/entretien/toilettage", name="veterinary-cat-grooming")
     */
    public function catGrooming(Request $request, Cat $cat, PetCareRepository $petCareRepository, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $petCares = $petCareRepository->findCatGroomings($cat);

        $paginatedPetCares = $paginator->paginate(
            $petCares,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('cat-interface/cat-petcare/cat_petcare_grooming.html.twig', [
            'controller_name' => 'CatPetCareController',
            'cat' => $cat,
            'paginatedPetCares' => $paginatedPetCares,
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/entretien/griffes", name="cat-claws")
     * @Route("/espace-veterinaire/chat/{id}/entretien/griffes", name="veterinary-cat-claws")
     */
    public function catClaws(Request $request, Cat $cat, PetCareRepository $petCareRepository, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $petCares = $petCareRepository->findCatClaws($cat);

        $paginatedPetCares = $paginator->paginate(
            $petCares,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('cat-interface/cat-petcare/cat_petcare_claws.html.twig', [
            'controller_name' => 'CatPetCareController',
            'cat' => $cat,
            'paginatedPetCares' => $paginatedPetCares,
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/entretien/yeux-et-oreilles", name="cat-eyesEars")
     * @Route("/espace-veterinaire/chat/{id}/entretien/yeux-et-oreilles", name="veterinary-cat-eyesEars")
     */
    public function catEyesEars(Request $request, Cat $cat, PetCareRepository $petCareRepository, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $petCares = $petCareRepository->findCatEyesEars($cat);

        $paginatedPetCares = $paginator->paginate(
            $petCares,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('cat-interface/cat-petcare/cat_petcare_eyes_ears.html.twig', [
            'controller_name' => 'CatPetCareController',
            'cat' => $cat,
            'paginatedPetCares' => $paginatedPetCares,
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/entretien/dents", name="cat-teeth")
     * @Route("/espace-veterinaire/chat/{id}/entretien/dents", name="veterinary-cat-teeth")
     */
    public function catTeeth(Request $request, Cat $cat, PetCareRepository $petCareRepository, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $petCares = $petCareRepository->findCatTeeth($cat);

        $paginatedPetCares = $paginator->paginate(
            $petCares,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('cat-interface/cat-petcare/cat_petcare_teeth.html.twig', [
            'controller_name' => 'CatPetCareController',
            'cat' => $cat,
            'paginatedPetCares' => $paginatedPetCares,
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/entretien/litière", name="cat-litterbox")
     * @Route("/espace-veterinaire/chat/{id}/entretien/litière", name="veterinary-cat-litterbox")
     */
    public function catLitterbox(Request $request, Cat $cat, PetCareRepository $petCareRepository, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $petCares = $petCareRepository->findCatLitterbox($cat);

        $paginatedPetCares = $paginator->paginate(
            $petCares,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('cat-interface/cat-petcare/cat_petcare_litterbox.html.twig', [
            'controller_name' => 'CatPetCareController',
            'cat' => $cat,
            'paginatedPetCares' => $paginatedPetCares,
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/entretien/notes", name="cat-notes")
     * @Route("/espace-veterinaire/chat/{id}/entretien/notes", name="veterinary-cat-notes")
     */
    public function catNotes(Request $request, Cat $cat, PetCareRepository $petCareRepository, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $petCares = $petCareRepository->findCatNotes($cat);

        $paginatedPetCares = $paginator->paginate(
            $petCares,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('cat-interface/cat-petcare/cat_petcare_notes.html.twig', [
            'controller_name' => 'CatPetCareController',
            'cat' => $cat,
            'paginatedPetCares' => $paginatedPetCares,
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/entretien/alimentation/ajouter", name="add-feeding")
     */
    public function addFeeding(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, Cat $cat): Response 
    {
        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $cat = $catRepository->findOneBy(['id' => $cat]);

        $petCare = new PetCare;

        $form = $this->createForm(CatPetCareFormType::class, $petCare, [
            'action' => $this->generateUrl('add-feeding', ['id' => $cat->getId(),
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getFoodType() == null || $form->getData()->getFoodQuantity() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Veuillez au minimum indiquer la date, le type de nourriture et la quantité.");

                return $this->redirectToRoute('cat-feeding', ['id' => $cat->getId() ]);
            }

            $petCare->setCat($cat);

            $manager->persist($petCare);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été ajoutée");

            return $this->redirectToRoute('cat-feeding', ['id' => $cat->getId() ]);

        } else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Veuillez au minimum indiquer la date, le type de nourriture et la quantité.");

            return $this->redirectToRoute('cat-feeding', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-petcare/_add_edit_cat_feeding.html.twig', [
            'controller_name' => 'CatPetCareController',
            'petCareForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/entretien/toilettage/ajouter", name="add-grooming")
     */
    public function addGrooming(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, Cat $cat): Response 
    {
        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $cat = $catRepository->findOneBy(['id' => $cat]);

        $petCare = new PetCare;

        $form = $this->createForm(CatPetCareFormType::class, $petCare, [
            'action' => $this->generateUrl('add-grooming', ['id' => $cat->getId(),
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getGrooming() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Les deux champs sont requis.");

                return $this->redirectToRoute('cat-grooming', ['id' => $cat->getId() ]);
            }

            $petCare->setCat($cat);

            $manager->persist($petCare);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été ajoutée");

            return $this->redirectToRoute('cat-grooming', ['id' => $cat->getId() ]);

        } else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Les deux champs sont requis.");

            return $this->redirectToRoute('cat-grooming', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-petcare/_add_edit_cat_grooming.html.twig', [
            'controller_name' => 'CatPetCareController',
            'petCareForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/entretien/griffes/ajouter", name="add-claws")
     */
    public function addClaws(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, Cat $cat): Response 
    {
        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $cat = $catRepository->findOneBy(['id' => $cat]);

        $petCare = new PetCare;

        $form = $this->createForm(CatPetCareFormType::class, $petCare, [
            'action' => $this->generateUrl('add-claws', ['id' => $cat->getId(),
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $petCare->setCat($cat);
            $petCare->setClaws(true);

            $manager->persist($petCare);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été ajoutée");

            return $this->redirectToRoute('cat-claws', ['id' => $cat->getId() ]);

        } else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir une date.");

            return $this->redirectToRoute('cat-claws', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-petcare/_add_edit_cat_claws.html.twig', [
            'controller_name' => 'CatPetCareController',
            'petCareForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/entretien/yeux-et-oreille/ajouter", name="add-eyesEars")
     */
    public function addEyesEars(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, Cat $cat): Response 
    {
        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $cat = $catRepository->findOneBy(['id' => $cat]);

        $petCare = new PetCare;

        $form = $this->createForm(CatPetCareFormType::class, $petCare, [
            'action' => $this->generateUrl('add-eyesEars', ['id' => $cat->getId(),
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getEyesEars() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir une date et au moins un des choix.");

                return $this->redirectToRoute('cat-eyesEars', ['id' => $cat->getId() ]);
            }

            $petCare->setCat($cat);

            $manager->persist($petCare);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été ajoutée");

            return $this->redirectToRoute('cat-eyesEars', ['id' => $cat->getId() ]);

        } else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir une date et au moins un des choix.");

            return $this->redirectToRoute('cat-eyesEars', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-petcare/_add_edit_cat_eyes_ears.html.twig', [
            'controller_name' => 'CatPetCareController',
            'petCareForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/entretien/dents/ajouter", name="add-teeth")
     */
    public function addTeeth(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, Cat $cat): Response 
    {
        $cat = $catRepository->findOneBy(['id' => $cat]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $petCare = new PetCare;

        $form = $this->createForm(CatPetCareFormType::class, $petCare, [
            'action' => $this->generateUrl('add-teeth', ['id' => $cat->getId(),
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getTeeth() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Les deux champs sont requis.");

                return $this->redirectToRoute('cat-teeth', ['id' => $cat->getId() ]);
            }

            $petCare->setCat($cat);

            $manager->persist($petCare);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été ajoutée");

            return $this->redirectToRoute('cat-teeth', ['id' => $cat->getId() ]);

        } else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Les deux champs sont requis.");

            return $this->redirectToRoute('cat-teeth', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-petcare/_add_edit_cat_teeth.html.twig', [
            'controller_name' => 'CatPetCareController',
            'petCareForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/entretien/litière/ajouter", name="add-litterbox")
     */
    public function addLitterbox(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, Cat $cat): Response 
    {
        $cat = $catRepository->findOneBy(['id' => $cat]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $petCare = new PetCare;

        $form = $this->createForm(CatPetCareFormType::class, $petCare, [
            'action' => $this->generateUrl('add-litterbox', ['id' => $cat->getId(),
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $petCare->setCat($cat);
            $petCare->setLitterbox(true);

            $manager->persist($petCare);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été ajoutée");

            return $this->redirectToRoute('cat-litterbox', ['id' => $cat->getId() ]);

        } else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir une date.");

            return $this->redirectToRoute('cat-litterbox', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-petcare/_add_edit_cat_litterbox.html.twig', [
            'controller_name' => 'CatPetCareController',
            'petCareForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/entretien/notes/ajouter", name="add-notes")
     */
    public function addNotes(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, Cat $cat): Response 
    {
        $cat = $catRepository->findOneBy(['id' => $cat]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $petCare = new PetCare;

        $form = $this->createForm(CatPetCareFormType::class, $petCare, [
            'action' => $this->generateUrl('add-notes', ['id' => $cat->getId(),
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getNotes() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Les deux champs sont requis.");

                return $this->redirectToRoute('cat-notes', ['id' => $cat->getId() ]);
            }

            $petCare->setCat($cat);

            $manager->persist($petCare);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été ajoutée");

            return $this->redirectToRoute('cat-notes', ['id' => $cat->getId() ]);

        } else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Les deux champs sont requis.");

            return $this->redirectToRoute('cat-notes', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-petcare/_add_edit_cat_notes.html.twig', [
            'controller_name' => 'CatPetCareController',
            'petCareForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{catId}/entretien/alimentation/{petCareId}/editer", name="edit-feeding")
     */
    public function editFeeding(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, PetCareRepository $petCareRepository, Cat $cat = null, PetCare $petCare = null): Response 
    {
        $catId = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catId]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $petCareId = $request->attributes->get('petCareId');
        $petCare = $petCareRepository->findOneBy(['id' => $petCareId]);

        $form = $this->createForm(CatPetCareFormType::class, $petCare, [
            'action' => $this->generateUrl('edit-feeding', ['catId' => $cat->getId(), 'petCareId' => $petCare->getId()
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getFoodType() == null || $form->getData()->getFoodQuantity() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été modifiée. Veuillez au minimum indiquer la date, le type de nourriture et la quantité.");

                return $this->redirectToRoute('cat-feeding', ['id' => $cat->getId() ]);
            }

            $manager->persist($petCare);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été modifiée");

            return $this->redirectToRoute('cat-feeding', ['id' => $cat->getId() ]);

        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été modifiée. Veuillez au minimum indiquer la date, le type de nourriture et la quantité.");

            return $this->redirectToRoute('cat-feeding', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-petcare/_add_edit_cat_feeding.html.twig', [
            'controller_name' => 'CatPetCareController',
            'petCareForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{catId}/entretien/toilettage/{petCareId}/editer", name="edit-grooming")
     */
    public function editGrooming(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, PetCareRepository $petCareRepository, Cat $cat = null, PetCare $petCare = null): Response 
    {
        $catId = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catId]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $petCareId = $request->attributes->get('petCareId');
        $petCare = $petCareRepository->findOneBy(['id' => $petCareId]);

        $form = $this->createForm(CatPetCareFormType::class, $petCare, [
            'action' => $this->generateUrl('edit-grooming', ['catId' => $cat->getId(), 'petCareId' => $petCare->getId()
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getGrooming() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été modifiée. Les deux champs sont requis.");

                return $this->redirectToRoute('cat-grooming', ['id' => $cat->getId() ]);
            }

            $manager->persist($petCare);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été modifiée");

            return $this->redirectToRoute('cat-grooming', ['id' => $cat->getId() ]);

        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été modifiée. Les deux champs sont requis.");

            return $this->redirectToRoute('cat-grooming', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-petcare/_add_edit_cat_grooming.html.twig', [
            'controller_name' => 'CatPetCareController',
            'petCareForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{catId}/entretien/griffes/{petCareId}/editer", name="edit-claws")
     */
    public function editClaws(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, PetCareRepository $petCareRepository, Cat $cat = null, PetCare $petCare = null): Response 
    {
        $catId = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catId]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $petCareId = $request->attributes->get('petCareId');
        $petCare = $petCareRepository->findOneBy(['id' => $petCareId]);

        $form = $this->createForm(CatPetCareFormType::class, $petCare, [
            'action' => $this->generateUrl('edit-claws', ['catId' => $cat->getId(), 'petCareId' => $petCare->getId()
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($petCare);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été modifiée");

            return $this->redirectToRoute('cat-claws', ['id' => $cat->getId() ]);

        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été modifiée. Veuillez saisir une date.");

            return $this->redirectToRoute('cat-claws', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-petcare/_add_edit_cat_claws.html.twig', [
            'controller_name' => 'CatPetCareController',
            'petCareForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{catId}/entretien/yeux-et-oreilles/{petCareId}/editer", name="edit-eyesEars")
     */
    public function editEyesEars(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, PetCareRepository $petCareRepository, Cat $cat = null, PetCare $petCare = null): Response 
    {
        $catId = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catId]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $petCareId = $request->attributes->get('petCareId');
        $petCare = $petCareRepository->findOneBy(['id' => $petCareId]);

        $form = $this->createForm(CatPetCareFormType::class, $petCare, [
            'action' => $this->generateUrl('edit-eyesEars', ['catId' => $cat->getId(), 'petCareId' => $petCare->getId()
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getEyesEars() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été modifiée. Vous devez saisir une date et au moins un des choix.");

                return $this->redirectToRoute('cat-eyesEars', ['id' => $cat->getId() ]);
            }

            $manager->persist($petCare);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été modifiée");

            return $this->redirectToRoute('cat-eyesEars', ['id' => $cat->getId() ]);

        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été modifiée. Vous devez saisir une date et au moins un des choix.");

            return $this->redirectToRoute('cat-eyesEars', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-petcare/_add_edit_cat_eyes_ears.html.twig', [
            'controller_name' => 'CatPetCareController',
            'petCareForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{catId}/entretien/dents/{petCareId}/editer", name="edit-teeth")
     */
    public function editTeeth(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, PetCareRepository $petCareRepository, Cat $cat = null, PetCare $petCare = null): Response 
    {
        $catId = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catId]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $petCareId = $request->attributes->get('petCareId');
        $petCare = $petCareRepository->findOneBy(['id' => $petCareId]);

        $form = $this->createForm(CatPetCareFormType::class, $petCare, [
            'action' => $this->generateUrl('edit-teeth', ['catId' => $cat->getId(), 'petCareId' => $petCare->getId()
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getTeeth() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été modifiée. Les deux champs sont requis.");

                return $this->redirectToRoute('cat-teeth', ['id' => $cat->getId() ]);
            }

            $manager->persist($petCare);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été modifiée");

            return $this->redirectToRoute('cat-teeth', ['id' => $cat->getId() ]);

        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été modifiée. Les deux champs sont requis.");

            return $this->redirectToRoute('cat-teeth', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-petcare/_add_edit_cat_teeth.html.twig', [
            'controller_name' => 'CatPetCareController',
            'petCareForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{catId}/entretien/litière/{petCareId}/editer", name="edit-litterbox")
     */
    public function editLitterbox(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, PetCareRepository $petCareRepository, Cat $cat = null, PetCare $petCare = null): Response 
    {
        $catId = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catId]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $petCareId = $request->attributes->get('petCareId');
        $petCare = $petCareRepository->findOneBy(['id' => $petCareId]);

        $form = $this->createForm(CatPetCareFormType::class, $petCare, [
            'action' => $this->generateUrl('edit-litterbox', ['catId' => $cat->getId(), 'petCareId' => $petCare->getId()
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($petCare);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été modifiée");

            return $this->redirectToRoute('cat-litterbox', ['id' => $cat->getId() ]);

        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été modifiée. Vous devez saisir une date.");

            return $this->redirectToRoute('cat-litterbox', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-petcare/_add_edit_cat_litterbox.html.twig', [
            'controller_name' => 'CatPetCareController',
            'petCareForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{catId}/entretien/notes/{petCareId}/editer", name="edit-notes")
     */
    public function editNotes(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, PetCareRepository $petCareRepository, Cat $cat = null, PetCare $petCare = null): Response 
    {
        $catId = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catId]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $petCareId = $request->attributes->get('petCareId');
        $petCare = $petCareRepository->findOneBy(['id' => $petCareId]);

        $form = $this->createForm(CatPetCareFormType::class, $petCare, [
            'action' => $this->generateUrl('edit-notes', ['catId' => $cat->getId(), 'petCareId' => $petCare->getId()
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getNotes() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été modifiée. Les deux champs sont requis.");

                return $this->redirectToRoute('cat-notes', ['id' => $cat->getId() ]);
            }

            $manager->persist($petCare);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été modifiée");

            return $this->redirectToRoute('cat-notes', ['id' => $cat->getId() ]);

        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été modifiée. Les deux champs sont requis.");

            return $this->redirectToRoute('cat-notes', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-petcare/_add_edit_cat_notes.html.twig', [
            'controller_name' => 'CatPetCareController',
            'petCareForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{catId}/entretien/alimentation/{petCareId}/supprimer", name="delete-petCare")
     * 
     * @Route("/espace-utilisateur/chat/{catId}/entretien/toilettage/{petCareId}/supprimer", name="delete-petcare")
     * 
     * @Route("/espace-utilisateur/chat/{catId}/entretien/griffes/{petCareId}/supprimer", name="delete-petcare")
     * 
     * @Route("/espace-utilisateur/chat/{catId}/entretien/yeux-et-oreilles/{petCareId}/supprimer", name="delete-petcare")
     * 
     * @Route("/espace-utilisateur/chat/{catId}/entretien/dents/{petCareId}/supprimer", name="delete-petcare")
     * 
     * @Route("/espace-utilisateur/chat/{catId}/entretien/litière/{petCareId}/supprimer", name="delete-petcare")
     * 
     * @Route("/espace-utilisateur/chat/{catId}/entretien/notes/{petCareId}/supprimer", name="delete-petcare")
     */
    public function deletePetCare(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, PetCareRepository $petCareRepository, Cat $cat = null, PetCare $petCare = null): Response 
    {
        $catId = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catId]);

        $user = $this->getUser();

        $className = get_class($user);

        $secureRoute = $this->isRouteSecure($className, $user, $cat);

        if ($secureRoute != null) {
            return $secureRoute;
        }
        
        $petCareId = $request->attributes->get('petCareId');
        $petCare = $petCareRepository->findOneBy(['id' => $petCareId]);

        $feeding = $petCare->getFoodQuantity();
        $grooming = $petCare->getgrooming();
        $claws = $petCare->getClaws();
        $eyesEars =  $petCare->getEyesEars();
        $teeth = $petCare->getTeeth();
        $litterbox = $petCare->getLitterbox();
        $notes = $petCare->getNotes();

        $form = $this->createForm(CatPetCareFormType::class, $petCare, [
            'action' => $this->generateUrl('delete-petCare', ['catId' => $cat->getId(), 'petCareId' => $petCare->getId()
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->remove($petCare);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été supprimée");

            if ($feeding != null) {
                return $this->redirectToRoute('cat-feeding', ['id' => $cat->getId() ]);
            } else if ($grooming != null) {
                return $this->redirectToRoute('cat-grooming', ['id' => $cat->getId() ]);
            } else if ($claws != null) {
                return $this->redirectToRoute('cat-claws', ['id' => $cat->getId() ]);
            }  else if ($eyesEars != null) {
                return $this->redirectToRoute('cat-eyesEars', ['id' => $cat->getId() ]);
            } else if ($teeth != null) {
                return $this->redirectToRoute('cat-teeth', ['id' => $cat->getId() ]);
            } else if ($litterbox != null) {
                return $this->redirectToRoute('cat-litterbox', ['id' => $cat->getId() ]);
            } else if ($notes != null) {
                return $this->redirectToRoute('cat-notes', ['id' => $cat->getId() ]);
            } else {
                return $this->redirectToRoute('cat-petCare', ['id' => $cat->getId() ]);
            } 
        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée' n'a pas été supprimée");

            if ($feeding  != null) {
                return $this->redirectToRoute('cat-feeding', ['id' => $cat->getId() ]);
            } else if ($grooming != null) {
                return $this->redirectToRoute('cat-grooming', ['id' => $cat->getId() ]);
            } else if ($claws != null) {
                return $this->redirectToRoute('cat-claws', ['id' => $cat->getId() ]);
            }  else if ($eyesEars != null) {
                return $this->redirectToRoute('cat-eyesEars', ['id' => $cat->getId() ]);
            } else if ($teeth != null) {
                return $this->redirectToRoute('cat-teeth', ['id' => $cat->getId() ]);
            } else if ($litterbox != null) {
                return $this->redirectToRoute('cat-litterbox', ['id' => $cat->getId() ]);
            } else if ($notes != null) {
                return $this->redirectToRoute('cat-notes', ['id' => $cat->getId() ]);
            } else {
                return $this->redirectToRoute('cat-petCare', ['id' => $cat->getId() ]);
            }
        }

        return $this->render('cat-interface/cat-petcare/_delete_cat_petcare.html.twig', [
            'controller_name' => 'CatPetCareController',
            'petCareForm' => $form->createView(),
            'catId' => $cat->getId(),
            'petCareId' => $petCare->getId(),
        ]);
    }
}
