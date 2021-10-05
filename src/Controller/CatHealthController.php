<?php

namespace App\Controller;

use App\Entity\Cat;
use App\Entity\Health;
use App\Form\CatHealthFormType;
use App\Repository\CatRepository;
use App\Repository\HealthRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CatHealthController extends AbstractController
{
    /**
     * @Route("/espace-utilisateur/chat/{id}/sante", name="cat-health")
     */
    public function catHealth(Cat $cat): Response
    {
        return $this->render('cat-interface/cat-health/cat_health.html.twig', [
            'controller_name' => 'CatHealthController',
            'cat' => $cat
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/sante/visite-veterinaire", name="cat-vetVisit")
     */
    public function catVetVisit(Request $request, Cat $cat, HealthRepository $healthRepository, PaginatorInterface $paginator): Response
    {
        $healths = $healthRepository->findCatVetVisits($cat);

        $paginatedHealths = $paginator->paginate(
            $healths,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('cat-interface/cat-health/cat_health_vet_visit.html.twig', [
            'controller_name' => 'CatHealthController',
            'cat' => $cat,
            'paginatedHealths' => $paginatedHealths,
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/sante/allergies", name="cat-allergy")
     */
    public function catAllergy(Request $request, Cat $cat, HealthRepository $healthRepository, PaginatorInterface $paginator): Response
    {
        $healths = $healthRepository->findCatAllergies($cat);

        $paginatedHealths = $paginator->paginate(
            $healths,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('cat-interface/cat-health/cat_health_allergy.html.twig', [
            'controller_name' => 'CatHealthController',
            'cat' => $cat,
            'paginatedHealths' => $paginatedHealths,
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/sante/maladies", name="cat-disease")
     */
    public function catDisease(Request $request, Cat $cat, HealthRepository $healthRepository, PaginatorInterface $paginator): Response
    {
        $healths = $healthRepository->findCatDiseases($cat);

        $paginatedHealths = $paginator->paginate(
            $healths,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('cat-interface/cat-health/cat_health_disease.html.twig', [
            'controller_name' => 'CatHealthController',
            'cat' => $cat,
            'paginatedHealths' => $paginatedHealths,
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/sante/blessures", name="cat-wound")
     */
    public function catWound(Request $request, Cat $cat, HealthRepository $healthRepository, PaginatorInterface $paginator): Response
    {
        $healths = $healthRepository->findCatWounds($cat);

        $paginatedHealths = $paginator->paginate(
            $healths,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('cat-interface/cat-health/cat_health_wound.html.twig', [
            'controller_name' => 'CatHealthController',
            'cat' => $cat,
            'paginatedHealths' => $paginatedHealths,
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/sante/chirurgie", name="cat-surgery")
     */
    public function catSurgery(Request $request, Cat $cat, HealthRepository $healthRepository, PaginatorInterface $paginator): Response
    {
        $healths = $healthRepository->findCatSurgeries($cat);

        $paginatedHealths = $paginator->paginate(
            $healths,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('cat-interface/cat-health/cat_health_surgery.html.twig', [
            'controller_name' => 'CatHealthController',
            'cat' => $cat,
            'paginatedHealths' => $paginatedHealths,
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/sante/analyses", name="cat-analysis")
     */
    public function catAnalysis(Request $request, Cat $cat, HealthRepository $healthRepository, PaginatorInterface $paginator): Response
    {
        $healths = $healthRepository->findCatAnalysis($cat);

        $paginatedHealths = $paginator->paginate(
            $healths,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('cat-interface/cat-health/cat_health_analysis.html.twig', [
            'controller_name' => 'CatHealthController',
            'cat' => $cat,
            'paginatedHealths' => $paginatedHealths,
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/sante/visite-veterinaire/ajouter", name="add-vetVisit")
     */
    public function addVetVisit(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, Cat $cat): Response 
    {
        $cat = $catRepository->findOneBy(['id' => $cat]);

        $health = new Health;

        $form = $this->createForm(CatHealthFormType::class, $health, [
            'action' => $this->generateUrl('add-vetVisit', ['id' => $cat->getId(),
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getVetVisitMotif() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir une date et le motif de la visite.");

                return $this->redirectToRoute('cat-vetVisit', ['id' => $cat->getId() ]);
            }

            $health->setCat($cat);

            $manager->persist($health);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été ajoutée");

            return $this->redirectToRoute('cat-vetVisit', ['id' => $cat->getId() ]);

        } else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir une date et le motif de la visite.");

            return $this->redirectToRoute('cat-vetVisit', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-health/_add_edit_cat_vet_visit.html.twig', [
            'controller_name' => 'CatHealthController',
            'healthForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/sante/allergies/ajouter", name="add-allergy")
     */
    public function addAllergy(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, Cat $cat): Response 
    {
        $cat = $catRepository->findOneBy(['id' => $cat]);

        $health = new Health;

        $form = $this->createForm(CatHealthFormType::class, $health, [
            'action' => $this->generateUrl('add-allergy', ['id' => $cat->getId(),
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getAllergy() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir au moins une date de début et un type d'allergie.");

                return $this->redirectToRoute('cat-allergy', ['id' => $cat->getId() ]);
            }

            $health->setCat($cat);

            $manager->persist($health);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été ajoutée");

            return $this->redirectToRoute('cat-allergy', ['id' => $cat->getId() ]);

        } else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir au moins une date de début et un type d'allergie.");

            return $this->redirectToRoute('cat-allergy', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-health/_add_edit_cat_allergy.html.twig', [
            'controller_name' => 'CatHealthController',
            'healthForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/sante/maladies/ajouter", name="add-disease")
     */
    public function addDisease(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, Cat $cat): Response 
    {
        $cat = $catRepository->findOneBy(['id' => $cat]);

        $health = new Health;

        $form = $this->createForm(CatHealthFormType::class, $health, [
            'action' => $this->generateUrl('add-disease', ['id' => $cat->getId(),
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getDisease() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir au moins une date de début et un nom de maladie.");

                return $this->redirectToRoute('cat-disease', ['id' => $cat->getId() ]);
            }

            $health->setCat($cat);

            $manager->persist($health);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été ajoutée");

            return $this->redirectToRoute('cat-disease', ['id' => $cat->getId() ]);

        } else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir au moins une date de début et un nom de maladie.");

            return $this->redirectToRoute('cat-disease', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-health/_add_edit_cat_disease.html.twig', [
            'controller_name' => 'CatHealthController',
            'healthForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/sante/blessures/ajouter", name="add-wound")
     */
    public function addWound(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, Cat $cat): Response 
    {
        $cat = $catRepository->findOneBy(['id' => $cat]);

        $health = new Health;

        $form = $this->createForm(CatHealthFormType::class, $health, [
            'action' => $this->generateUrl('add-wound', ['id' => $cat->getId(),
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getWound() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir au moins une date de début et un type de blessure.");

                return $this->redirectToRoute('cat-wound', ['id' => $cat->getId() ]);
            }

            $health->setCat($cat);

            $manager->persist($health);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été ajoutée");

            return $this->redirectToRoute('cat-wound', ['id' => $cat->getId() ]);

        } else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir au moins une date de début et un type de blessure.");

            return $this->redirectToRoute('cat-wound', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-health/_add_edit_cat_wound.html.twig', [
            'controller_name' => 'CatHealthController',
            'healthForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/sante/chirurgie/ajouter", name="add-surgery")
     */
    public function addSurgery(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, Cat $cat): Response 
    {
        $cat = $catRepository->findOneBy(['id' => $cat]);

        $health = new Health;

        $form = $this->createForm(CatHealthFormType::class, $health, [
            'action' => $this->generateUrl('add-surgery', ['id' => $cat->getId(),
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getSurgery() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir au moins une date et un type de chirurgie.");

                return $this->redirectToRoute('cat-surgery', ['id' => $cat->getId() ]);
            }

            $health->setCat($cat);

            $manager->persist($health);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été ajoutée");

            return $this->redirectToRoute('cat-surgery', ['id' => $cat->getId() ]);

        } else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir au moins une date et un type de chirurgie.");

            return $this->redirectToRoute('cat-surgery', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-health/_add_edit_cat_surgery.html.twig', [
            'controller_name' => 'CatHealthController',
            'healthForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}/sante/analyses/ajouter", name="add-analysis")
     */
    public function addAnalysis(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, Cat $cat): Response 
    {
        $cat = $catRepository->findOneBy(['id' => $cat]);

        $health = new Health;

        $form = $this->createForm(CatHealthFormType::class, $health, [
            'action' => $this->generateUrl('add-analysis', ['id' => $cat->getId(),
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getAnalysis() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir au moins une date et le motif de l'analyse.");

                return $this->redirectToRoute('cat-analysis', ['id' => $cat->getId() ]);
            }

            $health->setCat($cat);

            $manager->persist($health);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été ajoutée");

            return $this->redirectToRoute('cat-analysis', ['id' => $cat->getId() ]);

        } else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir au moins une date et le motif de l'analyse.");

            return $this->redirectToRoute('cat-analysis', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-health/_add_edit_cat_analysis.html.twig', [
            'controller_name' => 'CatHealthController',
            'healthForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{catId}/sante/visite-veterinaire/{healthId}/editer", name="edit-vetVisit")
     */
    public function editVetVisit(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, HealthRepository $healthRepository, Cat $cat = null, Health $health = null): Response 
    {
        $catId = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catId]);

        $healthId = $request->attributes->get('healthId');
        $health = $healthRepository->findOneBy(['id' => $healthId]);

        $form = $this->createForm(CatHealthFormType::class, $health, [
            'action' => $this->generateUrl('edit-vetVisit', ['catId' => $cat->getId(), 'healthId' => $health->getId()
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getVetVisitMotif() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été modifiée. Vous devez saisir une date et le motif de la visite.");

                return $this->redirectToRoute('cat-vetVisit', ['id' => $cat->getId() ]);
            }

            $manager->persist($health);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été modifiée");

            return $this->redirectToRoute('cat-vetVisit', ['id' => $cat->getId() ]);

        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été modifiée. Vous devez saisir une date et le motif de la visite.");

            return $this->redirectToRoute('cat-vetVisit', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-health/_add_edit_cat_vet_visit.html.twig', [
            'controller_name' => 'CatHealthController',
            'healthForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{catId}/sante/allergies/{healthId}/editer", name="edit-allergy")
     */
    public function editAllergy(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, HealthRepository $healthRepository, Cat $cat = null, Health $health = null): Response 
    {
        $catId = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catId]);

        $healthId = $request->attributes->get('healthId');
        $health = $healthRepository->findOneBy(['id' => $healthId]);

        $form = $this->createForm(CatHealthFormType::class, $health, [
            'action' => $this->generateUrl('edit-allergy', ['catId' => $cat->getId(), 'healthId' => $health->getId()
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getAllergy() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été modifiée. Vous devez saisir au moins une date de début et un type d'allergie.");

                return $this->redirectToRoute('cat-allergy', ['id' => $cat->getId() ]);
            }

            $manager->persist($health);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été modifiée");

            return $this->redirectToRoute('cat-allergy', ['id' => $cat->getId() ]);

        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été modifiée. Vous devez saisir au moins une date de début et un type d'allergie.");

            return $this->redirectToRoute('cat-allergy', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-health/_add_edit_cat_allergy.html.twig', [
            'controller_name' => 'CatHealthController',
            'healthForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{catId}/sante/maladies/{healthId}/editer", name="edit-disease")
     */
    public function editDisease(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, HealthRepository $healthRepository, Cat $cat = null, Health $health = null): Response 
    {
        $catId = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catId]);

        $healthId = $request->attributes->get('healthId');
        $health = $healthRepository->findOneBy(['id' => $healthId]);

        $form = $this->createForm(CatHealthFormType::class, $health, [
            'action' => $this->generateUrl('edit-disease', ['catId' => $cat->getId(), 'healthId' => $health->getId()
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getDisease() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été modifiée. Vous devez saisir au moins une date de début et un nom de maladie.");

                return $this->redirectToRoute('cat-disease', ['id' => $cat->getId() ]);
            }

            $manager->persist($health);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été modifiée");

            return $this->redirectToRoute('cat-disease', ['id' => $cat->getId() ]);

        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été modifiée. Vous devez saisir au moins une date de début et un nom de maladie.");

            return $this->redirectToRoute('cat-disease', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-health/_add_edit_cat_disease.html.twig', [
            'controller_name' => 'CatHealthController',
            'healthForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{catId}/sante/blessures/{healthId}/editer", name="edit-wound")
     */
    public function editWound(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, HealthRepository $healthRepository, Cat $cat = null, Health $health = null): Response 
    {
        $catId = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catId]);

        $healthId = $request->attributes->get('healthId');
        $health = $healthRepository->findOneBy(['id' => $healthId]);

        $form = $this->createForm(CatHealthFormType::class, $health, [
            'action' => $this->generateUrl('edit-wound', ['catId' => $cat->getId(), 'healthId' => $health->getId()
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getWound() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été modifiée. Vous devez saisir au moins une date de début et un type de blessure.");

                return $this->redirectToRoute('cat-wound', ['id' => $cat->getId() ]);
            }

            $manager->persist($health);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été modifiée");

            return $this->redirectToRoute('cat-wound', ['id' => $cat->getId() ]);

        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été modifiée. Vous devez saisir au moins une date de début et un type de blessure.");

            return $this->redirectToRoute('cat-wound', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-health/_add_edit_cat_wound.html.twig', [
            'controller_name' => 'CatHealthController',
            'healthForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{catId}/sante/chirurgie/{healthId}/editer", name="edit-surgery")
     */
    public function editSurgery(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, HealthRepository $healthRepository, Cat $cat = null, Health $health = null): Response 
    {
        $catId = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catId]);

        $healthId = $request->attributes->get('healthId');
        $health = $healthRepository->findOneBy(['id' => $healthId]);

        $form = $this->createForm(CatHealthFormType::class, $health, [
            'action' => $this->generateUrl('edit-surgery', ['catId' => $cat->getId(), 'healthId' => $health->getId()
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getSurgery() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été modifiée. Vous devez saisir au moins une date et un type de chirurgie.");

                return $this->redirectToRoute('cat-surgery', ['id' => $cat->getId() ]);
            }

            $manager->persist($health);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été modifiée");

            return $this->redirectToRoute('cat-surgery', ['id' => $cat->getId() ]);

        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été modifiée. Vous devez saisir au moins une date et un type de chirurgie.");

            return $this->redirectToRoute('cat-surgery', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-health/_add_edit_cat_surgery.html.twig', [
            'controller_name' => 'CatHealthController',
            'healthForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{catId}/sante/analyses/{healthId}/editer", name="edit-analysis")
     */
    public function editAnalysis(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, HealthRepository $healthRepository, Cat $cat = null, Health $health = null): Response 
    {
        $catId = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catId]);

        $healthId = $request->attributes->get('healthId');
        $health = $healthRepository->findOneBy(['id' => $healthId]);

        $form = $this->createForm(CatHealthFormType::class, $health, [
            'action' => $this->generateUrl('edit-analysis', ['catId' => $cat->getId(), 'healthId' => $health->getId()
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getAnalysis() == null) {
                $this->addFlash('danger', "L'entrée n'a pas été modifiée. Vous devez saisir au moins une date et le motif de l'analyse.");

                return $this->redirectToRoute('cat-analysis', ['id' => $cat->getId() ]);
            }

            $manager->persist($health);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été modifiée");

            return $this->redirectToRoute('cat-analysis', ['id' => $cat->getId() ]);

        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été modifiée. Vous devez saisir au moins une date et le motif de l'analyse.");

            return $this->redirectToRoute('cat-analysis', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-health/_add_edit_cat_analysis.html.twig', [
            'controller_name' => 'CatHealthController',
            'healthForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{catId}/sante/visite-veterinaire/{healthId}/supprimer", name="delete-health")
     * 
     * @Route("/espace-utilisateur/chat/{catId}/sante/allergies/{healthId}/supprimer", name="delete-health")
     * 
     * @Route("/espace-utilisateur/chat/{catId}/sante/maladies/{healthId}/supprimer", name="delete-health")
     * 
     * @Route("/espace-utilisateur/chat/{catId}/sante/blessures/{healthId}/supprimer", name="delete-health")
     * 
     * @Route("/espace-utilisateur/chat/{catId}/sante/chirurgie/{healthId}/supprimer", name="delete-health")
     * 
     * @Route("/espace-utilisateur/chat/{catId}/sante/analyses/{healthId}/supprimer", name="delete-health")
     */
    public function deleteHealth(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, HealthRepository $healthRepository, Cat $cat = null, Health $health = null): Response 
    {
        $catId = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catId]);

        $healthId = $request->attributes->get('healthId');
        $health = $healthRepository->findOneBy(['id' => $healthId]);

        $vetVisit = $health->getVetVisitMotif();
        $allergy = $health->getAllergy();
        $disease = $health->getDisease();
        $wound = $health->getWound();
        $surgery = $health->getSurgery();
        $analysis = $health->getAnalysis();

        $form = $this->createForm(CatHealthFormType::class, $health, [
            'action' => $this->generateUrl('delete-health', ['catId' => $cat->getId(), 'healthId' => $health->getId()
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->remove($health);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été supprimée");

            if ($vetVisit != null) {
                return $this->redirectToRoute('cat-vetVisit', ['id' => $cat->getId() ]);
            } else if ($allergy != null) {
                return $this->redirectToRoute('cat-allergy', ['id' => $cat->getId() ]);
            } else if ($disease != null) {
                return $this->redirectToRoute('cat-disease', ['id' => $cat->getId() ]);
            } else if ($wound != null) {
                return $this->redirectToRoute('cat-wound', ['id' => $cat->getId() ]);
            } else if ($surgery != null) {
                return $this->redirectToRoute('cat-surgery', ['id' => $cat->getId() ]);
            } else if ($analysis != null) {
                return $this->redirectToRoute('cat-analysis', ['id' => $cat->getId() ]);
            } else {
                return $this->redirectToRoute('cat-health', ['id' => $cat->getId() ]);
            } 
        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée' n'a pas été supprimée");

            if ($vetVisit  != null) {
                return $this->redirectToRoute('cat-vetVisit', ['id' => $cat->getId() ]);
            } else if ($allergy != null) {
                return $this->redirectToRoute('cat-allergy', ['id' => $cat->getId() ]);
            } else if ($disease != null) {
                return $this->redirectToRoute('cat-disease', ['id' => $cat->getId() ]);
            } else if ($wound != null) {
                return $this->redirectToRoute('cat-wound', ['id' => $cat->getId() ]);
            } else if ($surgery != null) {
                return $this->redirectToRoute('cat-surgery', ['id' => $cat->getId() ]);
            } else if ($analysis != null) {
                return $this->redirectToRoute('cat-analysis', ['id' => $cat->getId() ]);
            } else {
                return $this->redirectToRoute('cat-health', ['id' => $cat->getId() ]);
            }
        }

        return $this->render('cat-interface/cat-health/_delete_cat_health.html.twig', [
            'controller_name' => 'CatHealthController',
            'healthForm' => $form->createView(),
            'catId' => $cat->getId(),
            'healthId' => $health->getId(),
        ]);
    }
}
