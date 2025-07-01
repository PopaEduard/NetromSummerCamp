<?php

namespace App\Controller;

use App\Repository\UserDetailsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\UserDetails;
use App\Entity\User;

final class UserDetailsController extends AbstractController
{
    #[Route('/user/{id}', name: 'user_details')]
    public function index(UserRepository $userRepository, UserDetailsRepository $userDetailsRepository, int $id): Response {
        $user = $userRepository->findOneBy(['id' => $id]);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $userDetails = $userDetailsRepository->findOneBy(['user_id' => $user]);

        if (!$userDetails) {
            throw $this->createNotFoundException('UserDetails not found');
        }

        return $this->render('user_details/index.html.twig', [
            'details' => $userDetails,
            'user' => $user,
        ]);
    }
}
