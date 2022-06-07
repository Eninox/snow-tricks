<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Media;
use App\Entity\Message;
use App\Entity\Trick;
use App\Form\MessageType;
use App\Form\TrickEditType;
use App\Form\TrickType;
use App\Repository\CategoryRepository;
use App\Repository\MediaRepository;
use App\Repository\MessageRepository;
use App\Repository\TrickRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
            'tricks' => $trickRepository->findBy(['valid' => true]),
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/nouveau', name: 'app_trick_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TrickRepository $trickRepository): Response
    {
        // Creating form to create a new trick
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);
        $trick->setUserCreator($this->getUser());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($form->getData()->getMedia() as $media) {
                // Overriding the user input for choice type of media
                if ($media->getMediaFile() instanceof UploadedFile) {
                    if (str_starts_with($media->getMediaFile()->getMimeType(), 'image/')) {
                        $media->setType(Media::TYPE_PICTURE);

                    } else if (str_starts_with($media->getMediaFile()->getMimeType(), 'video/')) {
                        $media->setType(Media::TYPE_VIDEO_UPLOADED);
                    }
                } else {
                    $media->setType(Media::TYPE_VIDEO_STREAMED);
                }

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

    #[Route('/{slug}', name: 'app_trick_show', methods: ['GET', 'POST'])]
    public function show(Request $request, Trick $trick, MediaRepository $mediaRepository,
                         PaginatorInterface $paginator, MessageRepository $messageRepository): Response
    {
        if ($trick->getValid() === false && !$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('danger', 'Ce trick n\'est pas encore validé !');

            return $this->redirectToRoute('app_trick_index', [], Response::HTTP_SEE_OTHER);
        }

        // Configuring paginate of messages
        $messages = $paginator->paginate(
            $messageRepository->findBy(['trick' => $trick], ['createdAt' => 'DESC']), // Request for all messages of the trick
            $request->query->getInt('page', 1), // Page number (default is 1)
            5 // Limit messages per page
        );

        // Creating form view to add a message in trick show page
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $message->setUserAuthor($this->getUser());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setTrick($trick);
            $message->setCreatedAt(new \DateTimeImmutable());

            $messageRepository->add($message, true);
            $this->addFlash('success', 'Votre commentaire est créé !');

            return $this->redirectToRoute('app_trick_show', [
                'slug' => $trick->getSlug(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'medias' => $mediaRepository->findBy(['trick' => $trick]),
            'messages' => $messages,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/modifier', name: 'app_trick_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Trick $trick, TrickRepository $trickRepository): Response
    {
        // Checking if the trick is owned by the user
        if ($trick->getUserCreator() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('danger', 'Vous n\'avez pas le droit de modifier ce trick !');

            return $this->redirectToRoute('app_trick_index', [], Response::HTTP_SEE_OTHER);
        }

        $form = $this->createForm(TrickEditType::class, $trick);
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
        // Checking if the trick is owned by the user
        if ($trick->getUserCreator() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('danger', 'Vous n\'avez pas le droit de supprimer ce trick !');

            return $this->redirectToRoute('app_trick_index', [], Response::HTTP_SEE_OTHER);
        }

        if ($this->isCsrfTokenValid('delete' . $trick->getId(), $request->request->get('_token'))) {
            $trickRepository->remove($trick, true);
        }

        return $this->redirectToRoute('app_trick_index', [], Response::HTTP_SEE_OTHER);
    }

    // Add a route to show a category with their tricks
    #[Route('/category/{slug}', name: 'app_category_show', methods: ['GET'])]
    public function showCategory(Category $category, TrickRepository $trickRepository): Response
    {
        return $this->render('category/show.html.twig', [
            'tricks' => $trickRepository->findBy(['valid' => true]),
            'category' => $category,
        ]);
    }

    public function buttonCreateTrick(): Response
    {
        return $this->render('trick/_buttonCreateTrick.html.twig');
    }
}
