<?php

namespace App\Controller;

use App\Entity\Cat;
use App\Repository\PetCareRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CatPetCareController extends AbstractController
{
    /**
     * @Route("/espace-utilisateur/chat/{id}/entretien", name="cat-petcare")
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
    public function catHeat(Request $request, Cat $cat, PetCareRepository $petCareRepository, PaginatorInterface $paginator): Response
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
            'controller_name' => 'CatMeasuresController',
            'cat' => $cat,
            'paginatedPetCares' => $paginatedPetCares,
            'paginatedCurrentPetCares' => $paginatedCurrentPetCares,
            'currentPetCares' => $currentPetCares,
        ]);
    }
}
