<?php

namespace App\Controller;

use App\Repository\CatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
}
