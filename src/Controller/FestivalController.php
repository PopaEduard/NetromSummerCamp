<?php

namespace App\Controller;

use App\Entity\Editions;
use App\Entity\Ticket;
use App\Repository\EditionsRepository;
use App\Repository\FestivalArtistRepository;
use App\Repository\PurchaseRepository;
use App\Repository\ScheduleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Festival;
use App\Repository\FestivalRepository;
use Knp\Component\Pager\PaginatorInterface;
use App\Form\FestivalForm;


final class FestivalController extends AbstractController
{
    private int $ItemsPerPage = 5;

    #[Route('/festival', name: 'festival_list')]
    public function index(FestivalRepository $festivalRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $festivalRepository->createQueryBuilder('f')->getQuery();

        $festivals = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $this->ItemsPerPage
        );

        return $this->render('festival/index.html.twig', [
            'festivals' => $festivals,
        ]);
    }

    // Delete method
    #[Route('/festival/delete/{id}', name: 'delete_festival', methods: ['POST', 'DELETE'])]
    public function delete(
        FestivalRepository $festivalRepository,
        EditionsRepository $editionsRepository,
        ScheduleRepository $scheduleRepository,
        FestivalArtistRepository $festivalArtistRepository,
        PurchaseRepository $purchaseRepository,
        EntityManagerInterface $em,
        Request $request,
        int $id
    ): Response {
        $festival = $festivalRepository->find($id);

        if (!$festival) {
            throw $this->createNotFoundException('No festival found for id '.$id);
        }

        $submittedToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete'.$festival->getId(), $submittedToken)) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $purchases = $purchaseRepository->findBy(['festival_id' => $festival]);
        $ticket = new Ticket();
        foreach ($purchases as $purchase) {
            $purchase->setTypeId($ticket);
            $em->remove($purchase);
        }

        $editions = $editionsRepository->findBy(['festival_id' => $festival]);

        foreach ($editions as $edition) {
            $schedules = $scheduleRepository->findBy(['edition_id' => $edition]);
            foreach ($schedules as $schedule) {
                $em->remove($schedule);
            }

            $festivalArtists = $festivalArtistRepository->findBy(['edition_id' => $edition]);
            foreach ($festivalArtists as $fa) {
                $em->remove($fa);
            }

            $em->remove($edition);
        }

        $em->remove($festival);
        $em->flush();

        return $this->redirectToRoute('festival_list');
    }

    #[Route('/festival/add', name: 'add_festival')]
    public function new(EntityManagerInterface $em, Request $request): Response
    {
        $festival  = new Festival();
        $form = $this->createForm(FestivalForm::class, $festival);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $festival = $form->getData();

            $em->persist($festival);
            $em->flush();

            return $this->redirectToRoute('festival_list');
        }

        return $this->render('add_festival/index.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/festival/edit/{id}', name: 'edit_festival', methods: ['POST', 'GET'])]
    public function edit(
        int $id,
        FestivalRepository $festivalRepository,
        EntityManagerInterface $em,
        Request $request
    ): Response {
        $festival = $festivalRepository->find($id);

        if (!$festival) {
            throw $this->createNotFoundException('No festival found for id '.$id);
        }

        $form = $this->createForm(FestivalForm::class, $festival);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('festival_list');
        }

        return $this->render('edit_festival/index.html.twig', [
            'form' => $form,
            'festival' => $festival
        ]);
    }
}
