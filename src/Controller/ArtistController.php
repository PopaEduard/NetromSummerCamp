<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Form\ArtistForm;
use App\Repository\ArtistRepository;
use App\Repository\FestivalArtistRepository;
use App\Repository\ScheduleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

final class ArtistController extends AbstractController
{
    private int $itemsPerPage = 5;

    #[Route('/artist', name: 'artist_list')]
    public function index(ArtistRepository $artistRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $artistRepository->createQueryBuilder('a')
            ->orderBy('a.name', 'ASC')
            ->getQuery();

        $artists = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $this->itemsPerPage
        );

        return $this->render('artist/index.html.twig', [
            'artists' => $artists,
        ]);
    }

    #[Route('/artist/delete/{id}', name: 'delete_artist', methods: ['POST', 'DELETE'])]
    public function delete(
        EntityManagerInterface $em,
        ArtistRepository $artistRepository,
        ScheduleRepository $scheduleRepository,
        FestivalArtistRepository $festivalArtistRepository,
        Request $request,
        int $id
    ): Response {
        $artist = $artistRepository->find($id);

        if (!$artist) {
            throw $this->createNotFoundException('No Artist found for id '.$id);
        }

        $submittedToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete'.$artist->getId(), $submittedToken)) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $schedules = $scheduleRepository->findBy(['artist_id' => $artist]);
        foreach ($schedules as $schedule) {
            $em->remove($schedule);
        }

        $fas = $festivalArtistRepository->findBy(['artist_id' => $artist]);
        foreach ($fas as $fa) {
            $em->remove($fa);
        }

        $em->remove($artist);
        $em->flush();

        return $this->redirectToRoute('artist_list');
    }

    #[Route('/artist/add', name: 'add_artist')]
    public function new(EntityManagerInterface $em, Request $request): Response
    {
        $artist  = new Artist();
        $form = $this->createForm(ArtistForm::class, $artist);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $artist = $form->getData();

            $em->persist($artist);
            $em->flush();

            return $this->redirectToRoute('artist_list');
        }

        return $this->render('add_artist/index.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/artist/edit/{id}', name: 'edit_artist', methods: ['POST', 'GET'])]
    public function edit(
        int $id,
        ArtistRepository $artistRepository,
        EntityManagerInterface $em,
        Request $request
    ): Response {
        $artist = $artistRepository->find($id);

        if (!$artist) {
            throw $this->createNotFoundException('No artist found for id '.$id);
        }

        $form = $this->createForm(ArtistForm::class, $artist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('artist_list');
        }

        return $this->render('edit_artist/index.html.twig', [
            'form' => $form,
            'artist' => $artist
        ]);
    }
}
