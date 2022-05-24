<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Media;
use App\Entity\Trick;
use App\Form\TrickType;
use App\Repository\CategoryRepository;
use App\Repository\MediaRepository;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/trick')]
class TrickController extends AbstractController
{
    #[Route('/', name: 'app_trick_index', methods: ['GET'])]
    public function index(TrickRepository $trickRepository, CategoryRepository $categoryRepository): Response
    {
        return $this->render('trick/index.html.twig', [
            'tricks' => $trickRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/nouveau', name: 'app_trick_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TrickRepository $trickRepository): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);
        $trick->setUserCreator($this->getUser());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($form->getData()->getMedia() as $media) {
                if ($media->getMediaFile()->getMimeType() === 'image/jpg'
                    || $media->getMediaFile()->getMimeType() === 'image/jpeg'
                    || $media->getMediaFile()->getMimeType() === 'image/png'
                    || $media->getMediaFile()->getMimeType() === 'image/gif') {
                    $media->setType(Media::TYPE_PICTURE);

                } else if ($media->getMediaFile()->getMimeType() === 'video/mp4'
                    || $media->getMediaFile()->getMimeType() === 'video/webm'
                    || $media->getMediaFile()->getMimeType() === 'video/ogg'
                    || $media->getMediaFile()->getMimeType() === 'video/x-flv'
                    || $media->getMediaFile()->getMimeType() === 'video/avi') {
                    $media->setType(Media::TYPE_VIDEO_UPLOADED);
                }

                $media->setTitle($media->getMediaFile()->getClientOriginalName());
                $media->setTrick($trick);
            }

            $trickRepository->add($trick, true);

            $this->addFlash('success', 'Votre trick est créée !');

            return $this->redirectToRoute('app_trick_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('trick/new.html.twig', [
            'trick' => $trick,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_trick_show', methods: ['GET'])]
    public function show(Trick $trick): Response
    {
        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
        ]);
    }

    #[Route('/{id}/modifier', name: 'app_trick_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Trick $trick, TrickRepository $trickRepository): Response
    {
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trickRepository->add($trick, true);

            return $this->redirectToRoute('app_trick_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_trick_delete', methods: ['POST'])]
    public function delete(Request $request, Trick $trick, TrickRepository $trickRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $trick->getId(), $request->request->get('_token'))) {
            $trickRepository->remove($trick, true);
        }

        return $this->redirectToRoute('app_trick_index', [], Response::HTTP_SEE_OTHER);
    }

    // Add a route to show a category with their tricks
    #[Route('/category/{id}', name: 'app_category_show', methods: ['GET'])]
    public function showCategory(Category $category, TrickRepository $trickRepository): Response
    {
        return $this->render('category/show.html.twig', [
            'tricks' => $trickRepository->findAll(),
            'category' => $category,
        ]);
    }

    public function buttonCreateTrick(): Response
    {
        return $this->render('trick/_buttonCreateTrick.html.twig');
    }
}
