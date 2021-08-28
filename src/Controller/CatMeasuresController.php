<?php

namespace App\Controller;

use App\Entity\Cat;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CatMeasuresController extends AbstractController
{
    /**
     * @Route("/espace-utilisateur/chat/{id}/mesures", name="cat-measures")
     */
    public function catMeasures(Cat $cat): Response
    {
        return $this->render('cat-interface/cat-measures/cat_measures.html.twig', [
            'controller_name' => 'CatMeasuresController',
            'cat' => $cat
        ]);
    }
}
