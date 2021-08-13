<?php

namespace App\Controller;

use App\Entity\Cat;
use App\Repository\CatRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CatController extends AbstractController
{
    /**
     * @Route("/espace-utilisateur/liste-chats", name="cat-list")
     */
    public function catList(CatRepository $catRepository): Response
    {
        $user = $this->getUser();

        $cats = $catRepository->findBy(['owner' => $user]);
        dump($cats);

        return $this->render('cat-interface/cat_list.html.twig', [
            'controller_name' => 'CatController',
            'cats' => $cats,
        ]);
    }

    /**
     * @Route("/espace-utilisateur/chat/{id}", name="cat-detail")
     */
    public function catDetail(Cat $cat): Response
    {
        $microchip = $cat->getMicrochip();
        $regex = '/([0-9]{3})([0-9]{2})([0-9]{2})([0-9]{8})/';
        $replacement = "$1-$2-$3-$4";  
        $microchip = preg_replace($regex, $replacement, $microchip);

        return $this->render('cat-interface/cat_detail.html.twig', [
            'controller_name' => 'CatController',
            'cat' => $cat,
            'microchip' => $microchip,
        ]);
    }
}
