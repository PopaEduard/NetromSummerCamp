<?php

namespace App\Controller;

use App\Entity\UserDetails;
use App\Form\DetailsForm;
use App\Form\UserForm;
use App\Repository\PurchaseRepository;
use App\Repository\TicketRepository;
use App\Repository\UserDetailsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Ticket;
use App\Entity\User;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;

final class UserController extends AbstractController
{
    private int $ItemsPerPage = 5;

    #[Route('/user', name: 'user_list')]
    public function index(UserRepository $userRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $userRepository->createQueryBuilder('u')->getQuery();

        $users = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $this->ItemsPerPage
        );

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    // Delete method
    #[Route('/user/delete/{id}', name: 'delete_user', methods: ['POST', 'DELETE'])]
    public function delete(
        EntityManagerInterface $em,
        UserRepository $userRepository,
        UserDetailsRepository $userDetailsRepository,
        PurchaseRepository $purchaseRepository,
        Request $request,
        int $id
    ): Response {
        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('No User found for id '.$id);
        }

        $submittedToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete'.$user->getId(), $submittedToken)) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $userDetails = $userDetailsRepository->findOneBy(['user_id' => $user]);
        $purchases = $purchaseRepository->findBy(['user_id' => $user]);

        $ticket = new Ticket();
        foreach($purchases as $purchase) {
            $purchase->setTypeId($ticket);
            $em->remove($purchase);
        }

        $em->remove($userDetails);
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('user_list');
    }

    #[Route('/user/new', name: 'new_user', methods: ['POST', 'GET'])]
    public function new(
        EntityManagerInterface $em,
        Request $request,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $user = new User();
        $form = $this->createForm(UserForm::class, $user);

        $details = new UserDetails();
        $detailsForm = $this->createForm(DetailsForm::class, $details);

        $form->handleRequest($request);
        $detailsForm->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($detailsForm->isSubmitted() && $detailsForm->isValid()) {
                $plainPassword = $form->get('password')->getData();

                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $plainPassword
                );
                $user->setPassword($hashedPassword);

                $details->setUserId($user);

                $details->setLastLogin(new \DateTime());
                $details->setRegisterDate(new \DateTime());

                $user->setToken(bin2hex(random_bytes(36)));
                $user->setRole('ROLE_USER');

                $em->persist($user);
                $em->persist($details);

                $em->flush();

                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('add_user/index.html.twig', [
            'form' => $form->createView(),
            'detailsForm'  => $detailsForm->createView(),
        ]);
    }

    #[Route('/user/edit/{id}', name: 'edit_user', methods: ['POST', 'GET'])]
    public function edit(
        int $id,
        UserRepository $userRepository,
        EntityManagerInterface $em,
        Request $request,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('No festival found for id '.$id);
        }

        $form = $this->createForm(UserForm::class, $user, [
            'is_edit' => true
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $user->getPassword();

            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $plainPassword
            );
            $user->setPassword($hashedPassword);
            $em->flush();
            return $this->redirectToRoute('user_list');
        }

        return $this->render('edit_user/index.html.twig', [
            'form' => $form,
            'user' => $user
        ]);
    }
}
