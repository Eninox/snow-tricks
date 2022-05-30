<?php

namespace App\Controller;

use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {
        $categories = $doctrine->getRepository(Category::class)->findAll();

        return $this->render('default/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    public function header() : Response
    {
        return $this->render('default/_header.html.twig');
    }

    public function footer() : Response
    {
        return $this->render('default/_footer.html.twig');
    }
}
