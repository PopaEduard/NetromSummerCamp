<?php

namespace App\Controller;

use App\Repository\PurchaseRepository;
use App\Repository\TicketRepository;
use App\Repository\UserDetailsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
}
