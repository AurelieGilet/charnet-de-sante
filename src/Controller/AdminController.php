<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/espace-administrateur", name="admin-interface")
     */
    public function adminInterface(): Response
    {
        return $this->render('admin-interface/admin_interface.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}
