<?php

namespace App\Controller;

use DateTime;
use DateInterval;
use App\Entity\GuestCode;
use App\Repository\CatRepository;
use App\Repository\GuestCodeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GuestSecurityController extends AbstractController
{
    /**
     * @Route("/espace-utilisateur/chat/{catId}/nouveau-code", name="code-generator", methods={"POST"}, options={"expose"=true})
     */
    public function codeGenerator(Request $request, CatRepository $catRepository, GuestCodeRepository $guestCodeRepository, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {
        $user = $this->getUser();

        $guest = $user->getGuest();
        

        if ($request->isXmlHttpRequest()) {
            $catId = $request->attributes->get('_route_params');
            $cat = $catRepository->findOneBy(['id' => $catId]);

            $code = '';
            for ($k = 0; $k < 3; $k++) {
                $code .= mt_rand(1,9);
            }
            for ($k = 0; $k < 3; $k++) {
                $code .= chr(rand(65,90));
            }
            for ($k = 0; $k < 3; $k++) {
                $code .= mt_rand(1,9);
            }

            $validity = new DateTime();
            $validity->add(new DateInterval('PT10M'));

            $guestCode = $guestCodeRepository->findOneBy(['user' => $user]);

            if ($guestCode == null) {
                $guestCode = new GuestCode;
            }

            $hash = $hasher->hashPassword($guest, $code);

            $guestCode->setUser($user);
            $guestCode->setGuest($guest);
            $guestCode->setCat($cat);
            $guestCode->setCode($hash);
            $guestCode->setValidity($validity);
            $guestCode->setIsUsed(false);

            $manager->persist($guestCode);
            $manager->flush();

            return new JsonResponse($code);
        };

        return $this->render('security/_guest_code_generator.html.twig');
    }
}
