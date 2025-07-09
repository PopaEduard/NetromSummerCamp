<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\Stage;
use App\Form\FestivalArtistForm;
use App\Repository\EditionsRepository;
use App\Repository\FestivalArtistRepository;
use App\Repository\FestivalRepository;
use App\Repository\ScheduleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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

        $festivalArtists = $festivalArtistRepository->findBy(['edition_id' => $editions]);

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
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $edition = $editionsRepository->find($id);

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
    public function new(
        FestivalRepository $festivalRepository,
        EntityManagerInterface $em,
        Request $request,
        string $name
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $festival = $festivalRepository->findOneBy(['name' => $name]);

        $edition = new Editions();
        $editionForm = $this->createForm(EditionsForm::class, $edition);

        $artistForm = $this->createFormBuilder()
            ->add('artists', EntityType::class, [
                'class' => Artist::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => false,
                'attr' => ['class' => 'select2']
            ])
            ->getForm();

        $editionForm->handleRequest($request);
        $artistForm->handleRequest($request);

        if ($editionForm->isSubmitted() && $editionForm->isValid() &&
            $artistForm->isSubmitted() && $artistForm->isValid()) {

            $edition = $editionForm->getData();
            $edition->setFestivalId($festival);
            $em->persist($edition);

            $selectedArtists = $artistForm->get('artists')->getData();

            foreach ($selectedArtists as $artist) {
                $festivalArtist = new FestivalArtist();
                $festivalArtist->setEditionId($edition);
                $festivalArtist->setArtistId($artist);
                $em->persist($festivalArtist);
            }

            $em->flush();
            return $this->redirectToRoute('editions_list', ['name' => $name]);
        }

        return $this->render('add_edition/index.html.twig', [
            'form' => $editionForm->createView(),
            'artistForm' => $artistForm->createView(),
            'festival' => $festival,
        ]);
    }
}
