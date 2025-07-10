<?php

namespace App\Security;

use App\Repository\UserDetailsRepository;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private $em;
    private $router;
    private UserDetailsRepository $userDetailsRepository;

    public function __construct(EntityManagerInterface $em, RouterInterface $router, UserDetailsRepository $userDetailsRepository)
    {
        $this->em = $em;
        $this->router = $router;
        $this->userDetailsRepository = $userDetailsRepository;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response
    {
        $user = $token->getUser();

        $details = $this->userDetailsRepository->findOneBy(['user_id' => $user->getId()]);

        if (method_exists($details, 'setLastLogin')) {
            $dt = new \DateTime();
            $dt->add(new \DateInterval('PT1H'));
            $details->setLastLogin($dt);
            $this->em->persist($details);
            $this->em->flush();
        }

        return new RedirectResponse($this->router->generate('homepage'));
    }
}

