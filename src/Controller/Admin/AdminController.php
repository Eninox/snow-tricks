<?php

namespace App\Controller\Admin;

use App\Entity\Trick;
use App\Repository\CategoryRepository;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin_trick_index', methods: ['GET'])]
    public function index(TrickRepository $trickRepository): Response
    {
        return $this->render('admin/admin.html.twig', [
            'tricks' => $trickRepository->findBy(['valid' => false]),
        ]);
    }

    #[Route('/admin/accept/{id}', name: 'app_admin_trick_accept', methods: ['GET'])]
    public function acceptTrick(EntityManagerInterface $entityManager, TrickRepository $trickRepository, int $id): Response
    {
        $trick = $trickRepository->findOneBy(['id' => $id]);
        if ($trick) {
            $trick->setValid(true);
            $entityManager->flush();
        }
        $this->addFlash('success', 'Trick validée !');
        return $this->redirectToRoute('app_admin_trick_index');
    }

    #[Route('/admin/{id}', name: 'app_admin_trick_refuse', methods: ['POST'])]
    public function refuseTrick(Request $request, Trick $trick, TrickRepository $trickRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $trick->getId(), $request->request->get('_token'))) {
            $trickRepository->remove($trick, true);
        }
        $this->addFlash('success', 'Trick supprimée !');

        return $this->redirectToRoute('app_admin_trick_index', [], Response::HTTP_SEE_OTHER);
    }
}