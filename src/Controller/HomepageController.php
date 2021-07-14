<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(): Response
    {
        return $this->render('homepage/homepage.html.twig', [
            'controller_name' => 'HomepageController',
        ]);
    }

    /**
     * @Route("/aide", name="help")
     */
    public function help(): Response
    {
        return $this->render('homepage/help.html.twig', [
            'controller_name' => 'HomepageController',
        ]);
    }

    /**
     * @Route("/conditions-d-utilisation", name="legal-notice")
     */
    public function legalNotice(): Response
    {
        return $this->render('homepage/legal-notice.html.twig', [
            'controller_name' => 'HomepageController',
        ]);
    }
}
