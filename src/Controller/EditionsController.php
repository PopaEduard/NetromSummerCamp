<?php

namespace App\Controller;

use App\Form\FestivalArtistForm;
use App\Repository\EditionsRepository;
use App\Repository\FestivalArtistRepository;
use App\Repository\FestivalRepository;
use App\Repository\ScheduleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Editions;
use App\Entity\FestivalArtist;
use App\Form\EditionsForm;

final class EditionsController extends AbstractController
{
    #[Route('/festival/{name}/editions', name: 'editions_list')]
    public function index(FestivalRepository $festivalRepository, EditionsRepository $editionsRepository, FestivalArtistRepository $festivalArtistRepository, string $name): Response {
        $festival = $festivalRepository->findOneBy(['name' => $name]);

        if (!$festival) {
            throw $this->createNotFoundException('Festival not found');
        }

        $editions = $editionsRepository->findBy(['festival_id' => $festival]);

        if (!$editions) {
            throw $this->createNotFoundException('Editions not found');
        }

        $festivalArtists = $festivalArtistRepository->findBy(['edition_id' => $editions]);

        if (!$festivalArtists) {
            throw $this->createNotFoundException('FestivalArtist not found');
        }

        return $this->render('editions/index.html.twig', [
            'editions' => $editions,
            'festival' => $festival,
            'festivalArtists' => $festivalArtists,
        ]);
    }

    #[Route('/festival/{name}/editions/delete/{id}', name: 'delete_edition', methods: ['POST', 'DELETE'])]
    public function delete(
        EntityManagerInterface $em,
        Request $request,
        EditionsRepository $editionsRepository,
        FestivalArtistRepository $festivalArtistRepository,
        ScheduleRepository $scheduleRepository,
        string $name,
        int $id
    ): Response {
        $edition = $editionsRepository->find($id);

        if (!$edition) {
            throw $this->createNotFoundException('Edition not found');
        }

        $submittedToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete'.$edition->getId(), $submittedToken)) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $schedules = $scheduleRepository->findBy(['edition_id' => $edition]);
        foreach ($schedules as $schedule) {
            $em->remove($schedule);
        }

        $festivalArtists = $festivalArtistRepository->findBy(['edition_id' => $edition]);
        foreach ($festivalArtists as $fa) {
            $em->remove($fa);
        }

        $em->remove($edition);
        $em->flush();

        return $this->redirectToRoute('editions_list', ['name' => $name]);
    }

    #[Route('/festival/{name}/editions/add', name: 'add_edition')]
    public function new(FestivalRepository $festivalRepository, EntityManagerInterface $em, Request $request, string $name): Response
    {
        $festival = $festivalRepository->findOneBy(['name' => $name]);

        $edition  = new Editions();
        $festivalArtist = new FestivalArtist();
        $editionForm = $this->createForm(EditionsForm::class, $edition);
        $festivalArtistForm = $this->createForm(FestivalArtistForm::class, $festivalArtist);

        $editionForm->handleRequest($request);
        $festivalArtistForm->handleRequest($request);
        if ($editionForm->isSubmitted() && $editionForm->isValid() && $festivalArtistForm->isSubmitted() &&  $festivalArtistForm->isValid()) {
            $edition = $editionForm->getData();
            $festivalArtist = $festivalArtistForm->getData();

            $edition->setFestivalId($festival);
            $festivalArtist->setEditionId($edition);

            $em->persist($edition);
            $em->persist($festivalArtist);
            $em->flush();

            return $this->redirectToRoute('editions_list', ['name' => $name]);
        }

        return $this->render('add_edition/index.html.twig', [
            'editionForm' => $editionForm,
            'festivalArtistForm' => $festivalArtistForm,
            'festival' => $festival,
        ]);
    }
}
