<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Editions;
use App\Entity\Festival;
use App\Entity\FestivalArtist;

final class EditionsController extends AbstractController
{
    #[Route('/festival/{name}/editions', name: 'app_editions_by_festival')]
    public function index(EntityManagerInterface $entityManager, string $name): Response {
        $festival = $entityManager->getRepository(Festival::class)->findOneBy(['name' => $name]);

        if (!$festival) {
            throw $this->createNotFoundException('Festival not found');
        }

        $editions = $entityManager->getRepository(Editions::class)->findBy(['festival_id' => $festival]);

        if (!$editions) {
            throw $this->createNotFoundException('Editions not found');
        }

        $festivalArtists = $entityManager->getRepository(FestivalArtist::class)->findBy(['edition_id' => $editions]);

        if (!$festivalArtists) {
            throw $this->createNotFoundException('FestivalArtist not found');
        }

        return $this->render('editions/index.html.twig', [
            'editions' => $editions,
            'festival' => $festival,
            'festivalArtists' => $festivalArtists,
        ]);
    }
}
