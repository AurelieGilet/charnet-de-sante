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
                $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir une date et le motif de la visite.");

                return $this->redirectToRoute('cat-vetVisit', ['id' => $cat->getId() ]);
            }

            $manager->persist($health);
            $manager->flush();

            $this->addFlash('success', "L'entrée a été modifiée");

            return $this->redirectToRoute('cat-vetVisit', ['id' => $cat->getId() ]);

        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée n'a pas été ajoutée. Vous devez saisir une date et le motif de la visite.");

            return $this->redirectToRoute('cat-vetVisit', ['id' => $cat->getId() ]);
        }

        return $this->render('cat-interface/cat-health/_add_edit_cat_vet_visit.html.twig', [
            'controller_name' => 'CatHealthController',
            'healthForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{catId}/sante/visite-veterinaire/{healthId}/supprimer", name="delete-health")
     * 
     */
    public function deleteHealth(Request $request, EntityManagerInterface $manager, CatRepository $catRepository, HealthRepository $healthRepository, Cat $cat = null, Health $health = null): Response 
    {
        $catId = $request->attributes->get('catId');
        $cat = $catRepository->findOneBy(['id' => $catId]);

        $healthId = $request->attributes->get('healthId');
        $health = $healthRepository->findOneBy(['id' => $healthId]);

        $vetVisit = $health->getVetVisitMotif();

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
            } else {
                return $this->redirectToRoute('cat-health', ['id' => $cat->getId() ]);
            } 
        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "L'entrée' n'a pas été supprimée");

            if ($vetVisit  != null) {
                return $this->redirectToRoute('cat-vetVisit', ['id' => $cat->getId() ]);
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
