<?php

namespace App\Controller;

use App\Entity\Festival;
use App\Entity\Purchase;
use App\Form\PurchaseForm;
use App\Repository\EditionsRepository;
use App\Repository\TicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Ticket;
use App\Repository\FestivalRepository;
use App\Repository\UserDetailsRepository;
use App\Repository\UserRepository;
use App\Repository\PurchaseRepository;
use Knp\Component\Pager\PaginatorInterface;
use function PHPUnit\Framework\throwException;

final class PurchaseController extends AbstractController
{
    private int $ItemsPerPage = 1;

    #[Route('/user/{id}/purchase', name: 'purchase_list')]
    public function index(UserRepository $userRepository, PurchaseRepository $purchaseRepository, UserDetailsRepository $userDetailsRepository, int $id): Response {
        $user = $userRepository->findOneBy(['id' => $id]);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $userDetails = $userDetailsRepository->findOneBy(['user_id' => $user]);

        if(!$userDetails) {
            throw $this->createNotFoundException('UserDetails not found');
        }

        $purchases = $purchaseRepository->findBy(['user_id' => $user]);

        return $this->render('purchase/index.html.twig', [
            'purchases' => $purchases,
            'user' => $user,
            'details' => $userDetails,
        ]);
    }

    // Delete method
    #[Route('/purchase/delete/{id}', name: 'delete_purchase', methods: ['POST', 'DELETE'])]
    public function delete(
        PurchaseRepository $purchaseRepository,
        EntityManagerInterface $em,
        Request $request,
        int $id,
    ): Response {
        $purchase = $purchaseRepository->find($id);

        if (!$purchase) {
            throw $this->createNotFoundException('No Purchase found for id '.$id);
        }

        $submittedToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete'.$purchase->getId(), $submittedToken)) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $ticket = new Ticket();
        $purchase->setTypeId($ticket);

        $user_id = $purchase->getUserId()->getId();

        $em->remove($purchase);
        $em->flush();

        return $this->redirectToRoute('purchase_list', ['id' => $user_id]);
    }

    #[Route('festival/{id}/buy', name: 'buy_ticket', methods: ['GET', 'POST'])]
    public function buy(
        Request $request,
        TicketRepository $ticketRepository,
        FestivalRepository $festivalRepository,
        EntityManagerInterface $em,
        int $id): Response
    {
        $ticketTypes = $ticketRepository->findAll();
        $festival = $festivalRepository->findOneBy(['id' => $id]);

        if ($request->isMethod('POST')) {
            $ticketType = $request->request->get('ticketType');
            $ticket = $ticketRepository->find($ticketType);

            if (!$ticket) {
                $this->addFlash('error', 'Invalid ticket type selected.');
                return $this->redirectToRoute('buy_ticket', ['id' => $festival->getId()]);
            }

            $purchase = new Purchase();
            $purchase->setTypeId($ticket);
            $purchase->setFestivalId($festival);
            $purchase->setUserId($this->getUser());
            $purchase->setUsed(false);

            $em->persist($purchase);
            $em->flush();

            return $this->redirectToRoute('purchase_list',  ['id' => $this->getUser()->getId()]);
        }

        return $this->render('purchase/buy.html.twig', [
            'festival' => $festival,
            'ticketTypes' => $ticketTypes,
        ]);
    }

}
