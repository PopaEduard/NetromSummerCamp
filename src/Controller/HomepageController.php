<?php

namespace App\Controller;

use App\Repository\FestivalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(FestivalRepository $festivalRepo): Response
    {
        return $this->render('homepage/index.html.twig', [
            'featuredFestivals' => $festivalRepo->findBy([], ['id' => 'DESC'], 3),
            'user' => $this->getUser()
        ]);
    }
}
