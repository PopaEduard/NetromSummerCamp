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
    public function editionsByFestival(
        EntityManagerInterface $entityManager,
        string $name
    ): Response {
        $festival = $entityManager->getRepository(Festival::class)->findOneBy(['name' => $name]);

        $editions = $entityManager->getRepository(Editions::class)->findBy(['festival_id' => $festival]);

        $festivalArtists = $entityManager->getRepository(FestivalArtist::class)->findBy(['edition_id' => $editions]);

        return $this->render('editions/index.html.twig', [
            'editions' => $editions,
            'festival' => $festival,
            'festivalArtists' => $festivalArtists,
        ]);
    }
}
