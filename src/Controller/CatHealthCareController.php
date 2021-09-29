<?php

namespace App\Controller;

use App\Entity\Cat;
use App\Entity\HealthCare;
use App\Repository\CatRepository;
use App\Form\CatHealthCareFormType;
use App\Repository\HealthCareRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CatHealthCareController extends AbstractController
{
    /**
     * @Route("/espace-utilisateur/chat/{id}/soins", name="cat-healthCare")
     */
    public function catHealthCare(Cat $cat): Response
    {
        return $this->render('cat-interface/cat-healthcare/cat_healthCare.html.twig', [
            'controller_name' => 'CatHealthCareController',
            'cat' => $cat
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/soins/vaccin", name="cat-vaccine")
     */
    public function catVaccine(Request $request, Cat $cat, HealthCareRepository $healthCareRepository, PaginatorInterface $paginator): Response
    {
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
     */
    public function catDewormer(Request $request, Cat $cat, HealthCareRepository $healthCareRepository, PaginatorInterface $paginator): Response
    {
        $healthCares = $healthCareRepository->findCatDewormer($cat);

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
     * @Route("/espace-utilisateur/chat/{id}/soins/vaccin/ajouter", name="add-vaccine")
     */
    public function addVaccine(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, Cat $cat): Response 
    {
        $cat = $catRepository->findOneBy(['id' => $cat]);

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

        $healthCare = new HealthCare;

        $form = $this->createForm(CatHealthCareFormType::class, $healthCare, [
            'action' => $this->generateUrl('add-dewormer', ['id' => $cat->getId(),
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getDate() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir une date.");

                return $this->redirectToRoute('cat-dewormer', ['id' => $cat->getId() ]);
            }

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
     * @Route("/espace-utilisateur/chat/{catId}/soins/vaccin/{healthCareId}/editer", name="edit-vaccine")
     */
    public function editVaccine(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, HealthCareRepository $healthCareRepository, Cat $cat = null, HealthCare $healthCare = null): Response 
    {
        $catId = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catId]);

        $healthCareId = $request->attributes->get('healthCareId');
        $healthCare = $healthCareRepository->findOneBy(['id' => $healthCareId]);

        $form = $this->createForm(CatHealthCareFormType::class, $healthCare, [
            'action' => $this->generateUrl('edit-vaccine', ['catId' => $cat->getId(), 'healthCareId' => $healthCare->getId()
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getVaccine() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir une date et au moins un type de vaccin.");

                return $this->redirectToRoute('cat-vaccine', ['id' => $cat->getId() ]);
            }

            $manager->persist($healthCare);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été modifiée");

            return $this->redirectToRoute('cat-vaccine', ['id' => $cat->getId() ]);

        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir une date et au moins un type de vaccin.");

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

        $healthCareId = $request->attributes->get('healthCareId');
        $healthCare = $healthCareRepository->findOneBy(['id' => $healthCareId]);

        $form = $this->createForm(CatHealthCareFormType::class, $healthCare, [
            'action' => $this->generateUrl('edit-dewormer', ['catId' => $cat->getId(), 'healthCareId' => $healthCare->getId()
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getDate() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir une date.");

                return $this->redirectToRoute('cat-dewormer', ['id' => $cat->getId() ]);
            }

            $manager->persist($healthCare);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été modifiée");

            return $this->redirectToRoute('cat-dewormer', ['id' => $cat->getId() ]);

        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir une date.");

            return $this->redirectToRoute('cat-dewormer', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-healthCare/_add_edit_cat_dewormer.html.twig', [
            'controller_name' => 'CatHealthCareController',
            'healthCareForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{catId}/soins/vaccin/{healthCareId}/supprimer", name="delete-healthCare")
     * 
     * @Route("/espace-utilisateur/chat/{catId}/soins/vermifuge/{healthCareId}/supprimer", name="delete-healthCare")
     */
    public function deleteHealthCare(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, HealthCareRepository $healthCareRepository, Cat $cat = null, HealthCare $healthCare = null): Response 
    {
        $catId = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catId]);

        $healthCareId = $request->attributes->get('healthCareId');
        $healthCare = $healthCareRepository->findOneBy(['id' => $healthCareId]);

        $vaccine = $healthCare->getVaccine();
        $dewormer = $healthCare->getDewormer();

        $form = $this->createForm(CatHealthCareFormType::class, $healthCare, [
            'action' => $this->generateUrl('delete-healthCare', ['catId' => $cat->getId(), 'healthCareId' => $healthCare->getId()
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->remove($healthCare);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été supprimée");

            if ($vaccine != null) {
                return $this->redirectToRoute('cat-vaccine', ['id' => $cat->getId() ]);
            } else if ($dewormer != null) {
                return $this->redirectToRoute('cat-dewormer', ['id' => $cat->getId() ]);
            } else {
                return $this->redirectToRoute('cat-healthCare', ['id' => $cat->getId() ]);
            } 
        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée' n'a pas été supprimée");

            if ($vaccine  != null) {
                return $this->redirectToRoute('cat-vaccine', ['id' => $cat->getId() ]);
            } else if ($dewormer != null) {
                return $this->redirectToRoute('cat-dewormer', ['id' => $cat->getId() ]);
            } else {
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
