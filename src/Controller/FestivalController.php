<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Festival;

final class FestivalController extends AbstractController
{
    #[Route('/festival/{page}', name: 'app_festival', requirements: ['page' => '\d+'], defaults: ['page' => 1])]
    public function index(EntityManagerInterface $entityManager, int $page = 1): Response
    {
        $perPage = 5;
        $start = ($page - 1) * $perPage + 1;
        $total = $entityManager->getRepository(Festival::class)->count([]);
        $end = min($start + $perPage - 1, $total);

        $offset = ($page - 1) * $perPage;

        $repository = $entityManager->getRepository(Festival::class);

        $festivals = $repository->findBy([], null, $perPage, $offset);

        $totalPages = (int) ceil($total / $perPage);

        return $this->render('festival/index.html.twig', [
            'festivals' => $festivals,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'start' => $start,
            'perPage' => $perPage,
            'total' => $total,
            'end' => $end,
        ]);
    }


}
