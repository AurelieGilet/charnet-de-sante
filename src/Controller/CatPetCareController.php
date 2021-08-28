<?php

namespace App\Controller;

use App\Entity\Cat;
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
}
