<?php

namespace App\Controller;

use App\Entity\Cat;
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
}
