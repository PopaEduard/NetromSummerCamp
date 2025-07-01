<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Festival;
use App\Repository\FestivalRepository;
use Knp\Component\Pager\PaginatorInterface;

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
        EntityManagerInterface $entityManager,
        Request $request,
        int $id
    ): Response {
        $festival = $entityManager->getRepository(Festival::class)->find($id);

        if (!$festival) {
            throw $this->createNotFoundException('No festival found for id '.$id);
        }

        $submittedToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete'.$festival->getId(), $submittedToken)) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $entityManager->remove($festival);
        $entityManager->flush();

        return $this->redirectToRoute('festival_list');
    }
}
