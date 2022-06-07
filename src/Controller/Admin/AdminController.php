<?php

namespace App\Controller\Admin;

use App\Entity\Trick;
use App\Repository\CategoryRepository;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin_trick_index', methods: ['GET'])]
    public function index(TrickRepository $trickRepository): Response
    {
        return $this->render('admin/admin.html.twig', [
            'tricks' => $trickRepository->findAll(),
        ]);
    }

    #[Route('/admin/valid/{id}', name: 'app_admin_trick_valid', methods: ['GET'])]
    public function validTrick(EntityManagerInterface $entityManager, TrickRepository $trickRepository, int $id): Response
    {
        $trick = $trickRepository->findOneBy(['id' => $id]);
        if ($trick) {
            $trick->setValid(true);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_admin_trick_index');
    }
//        return $this->render('admin/admin.html.twig', [
//            'tricks' => $trickRepository->findAll(),
//        ]);
//    }
}