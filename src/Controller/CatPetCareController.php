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
    /**
     * @Route("/espace-utilisateur/chat/{id}/entretien", name="cat-petCare")
     */
    public function catPetcare(Cat $cat): Response
    {
        return $this->render('cat-interface/cat-petcare/cat_petcare.html.twig', [
            'controller_name' => 'CatPetCareController',
            'cat' => $cat
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/entretien/alimentation", name="cat-feeding")
     */
    public function catFeeding(Request $request, Cat $cat, PetCareRepository $petCareRepository, PaginatorInterface $paginator): Response
    {
        $petCares = $petCareRepository->findCatFeedings($cat);

        $paginatedPetCares = $paginator->paginate(
            $petCares,
            $request->query->getInt('page', 1),
            5
        );

        $currentPetCares = $petCareRepository->findCatCurrentFeedings($cat);

        $paginatedCurrentPetCares = $paginator->paginate(
            $currentPetCares,
            $request->query->getInt('page', 1),
            5
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
     */
    public function catGrooming(Request $request, Cat $cat, PetCareRepository $petCareRepository, PaginatorInterface $paginator): Response
    {
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
     * @Route("/espace-utilisateur/chat/{id}/entretien/alimentation/ajouter", name="add-feeding")
     */
    public function addFeeding(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, Cat $cat): Response 
    {
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
     * @Route("/espace-utilisateur/chat/{catId}/entretien/alimentation/{petCareId}/editer", name="edit-feeding")
     */
    public function editFeeding(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, PetCareRepository $petCareRepository, Cat $cat = null, PetCare $petCare = null): Response 
    {
        $catId = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catId]);

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

        $petCareId = $request->attributes->get('petCareId');
        $petCare = $petCareRepository->findOneBy(['id' => $petCareId]);

        $form = $this->createForm(CatPetCareFormType::class, $petCare, [
            'action' => $this->generateUrl('edit-grooming', ['catId' => $cat->getId(), 'petCareId' => $petCare->getId()
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getGrooming() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Les deux champs sont requis.");

                return $this->redirectToRoute('cat-grooming', ['id' => $cat->getId() ]);
            }

            $manager->persist($petCare);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été modifiée");

            return $this->redirectToRoute('cat-grooming', ['id' => $cat->getId() ]);

        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Les deux champs sont requis.");

            return $this->redirectToRoute('cat-grooming', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-petcare/_add_edit_cat_grooming.html.twig', [
            'controller_name' => 'CatPetCareController',
            'petCareForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{catId}/entretien/alimentation/{petCareId}/supprimer", name="delete-petCare")
     * 
     * @Route("/espace-utilisateur/chat/{catId}/entretien/toilettage/{petCareId}/supprimer", name="delete-petcare")
     */
    public function deletePetCare(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, PetCareRepository $petCareRepository, Cat $cat = null, PetCare $petCare = null): Response 
    {
        $catId = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catId]);

        $petCareId = $request->attributes->get('petCareId');
        $petCare = $petCareRepository->findOneBy(['id' => $petCareId]);

        $feeding = $petCare->getFoodQuantity();
        $grooming = $petCare->getgrooming();

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
            } /*else if ($heat != null) {
                return $this->redirectToRoute('cat-heat', ['id' => $cat->getId() ]);
            } */else {
                return $this->redirectToRoute('cat-petCare', ['id' => $cat->getId() ]);
            } 
        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée' n'a pas été supprimée");

            if ($feeding  != null) {
                return $this->redirectToRoute('cat-feeding', ['id' => $cat->getId() ]);
            } else if ($grooming != null) {
                return $this->redirectToRoute('cat-grooming', ['id' => $cat->getId() ]);
            } /*else if ($heat != null) {
                return $this->redirectToRoute('cat-heat', ['id' => $cat->getId() ]);
            }*/ else {
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
