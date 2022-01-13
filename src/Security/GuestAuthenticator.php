<?php

namespace App\Security;

use DateTime;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;

class GuestAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'guest-login';

    private UrlGeneratorInterface $urlGenerator;

    private UserRepository $userRepository;

    private RequestStack $requestStack;

    private EntityManagerInterface $manager;

    public function __construct(UrlGeneratorInterface $urlGenerator, UserRepository $userRepository, RequestStack $requestStack, EntityManagerInterface $manager)
    {
        $this->urlGenerator = $urlGenerator;
        $this->userRepository = $userRepository;
        $this->requestStack = $requestStack;
        $this->manager = $manager;
    }

    public function authenticate(Request $request): PassportInterface
    {
        $code = $request->request->all();

        if ($code) {
            // We extract the user Id from the code entered by the guest
            $userId = strtok($code['password'], '-');

            // We check the Id extracted is a number and nothing else
            if (is_numeric($userId)) {
                $user =  $this->userRepository->findOneBy(['id' => $userId]);

                if ($user) {
                    $guestCode = $user->getGuestCode();
                } else {
                    throw new AuthenticationException("Invalid code");
                }
                
                $validity = new DateTime();

                // We check the validity (10min) of the code and if it has already been used
                if ($guestCode->getValidity() > $validity && $guestCode->getIsUsed() == false) {
                    // We check if it is the right code
                    if (password_verify($code['password'], $guestCode->getCode())) {
                        $guest = $guestCode->getGuest();
                        $username = $guest->getUserIdentifier();
                        $cat = $guestCode->getCat();

                        // We store the cat Id in session to use it in the onAuthenticationSuccess method for redirection
                        $session = $this->requestStack->getSession();
                        $session->set('catId', $cat->getId());

                        // We indicate the code is used
                        $guestCode->setIsUsed(true);

                        $this->manager->persist($guestCode);
                        $this->manager->flush();

                        return new Passport(
                            new UserBadge($username),
                            new PasswordCredentials($request->request->get('password', '')),
                            [
                                new CsrfTokenBadge('authenticate', $request->get('_csrf_token')),
                            ]
                        );
                    }   
                } else {
                    throw new AuthenticationException("Invalid code");  
                }
            } else {
                throw new AuthenticationException("Invalid code"); 
            }
        } 
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        $session = $this->requestStack->getSession();

        $cat = $session->get('catId');

        return new RedirectResponse($this->urlGenerator->generate(
            'veterinary-cat-detail', ['id' => $cat]
        ));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        // Intelephense indicate getFlashBag as an undefined method but she exists and works perfectly
        $request->getSession()->getFlashBag()->add('danger', "Ce code n'est pas valide");

        return new RedirectResponse($this->urlGenerator->generate('homepage'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
