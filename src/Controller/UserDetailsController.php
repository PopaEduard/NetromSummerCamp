<?php

namespace App\Controller;

use App\Form\DetailsForm;
use App\Repository\UserDetailsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserDetailsController extends AbstractController
{
    #[Route('/user/{id}', name: 'user_details', requirements: ['id' => '\d+'])]
    public function index(UserRepository $userRepository, UserDetailsRepository $userDetailsRepository, int $id): Response {
        $user = $this->getUser();
        if (!$user || $user->getId() !== $id && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createNotFoundException('User not allowed on this page');
        }

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

    #[Route('/user/{id}/edit', name: 'edit_details', methods: ['POST', 'GET'])]
    public function edit(
        int $id,
        UserDetailsRepository $detailsRepository,
        UserRepository $userRepository,
        EntityManagerInterface $em,
        Request $request
    ): Response {
        $user = $this->getUser();
        if ((!$user || ($user->getId() !== $id)) && (!$this->isGranted('ROLE_ADMIN'))) {
            return $this->redirectToRoute('access_denied');
        }

        $details = $detailsRepository->find($id);
        $user = $userRepository->findOneBy(['id' => $id]);

        if (!$details) {
            throw $this->createNotFoundException('No details found for id '.$id);
        }

        $form = $this->createForm(DetailsForm::class, $details);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('user_details', ['id' => $id]);
        }

        return $this->render('user_details/edit.html.twig', [
            'form' => $form,
            'details' => $details,
            'user' => $user,
        ]);
    }
}
