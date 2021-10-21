<?php

namespace App\Controller;

use App\Repository\FAQRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function home(): Response
    {
        return $this->render('homepage/homepage.html.twig', [
            'controller_name' => 'BaseController',
        ]);
    }

    /**
     * @Route("/aide", name="help")
     */
    public function help(Request $request,FAQRepository $faqRepository, PaginatorInterface $paginator): Response
    {
        $faqs = $faqRepository->findBy(array(),
                                       array('id'=>'DESC') 
                                );

        $paginatedFAQ = $paginator->paginate(
            $faqs,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('homepage/help.html.twig', [
            'controller_name' => 'BaseController',
            'paginatedFAQ' => $paginatedFAQ,
        ]);
    }

    /**
     * @Route("/conditions-d-utilisation", name="legal-notice")
     */
    public function legalNotice(): Response
    {
        return $this->render('homepage/legal_notice.html.twig', [
            'controller_name' => 'BaseController',
        ]);
    }

    /**
     * @Route("/espace-utilisateur", name="user-interface")
     */
    public function userInterface(): Response
    {
        return $this->render('user-interface/user_interface.html.twig', [
            'controller_name' => 'BaseController',
        ]);
    }
}
