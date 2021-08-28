<?php

namespace App\Controller;

use App\Entity\Cat;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CatHealthCareController extends AbstractController
{
    /**
     * @Route("/espace-utilisateur/chat/{id}/soins", name="cat-healthcare")
     */
    public function catHealthcare(Cat $cat): Response
    {
        return $this->render('cat-interface/cat-healthcare/cat_healthcare.html.twig', [
            'controller_name' => 'CatHealthCareController',
            'cat' => $cat
        ]);
    }
}