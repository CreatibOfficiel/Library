<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Comment;
use App\Form\BookType;
use App\Form\CommentType;
use App\Repository\BookRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class for Book CRUD
 * @package App\Controller
 * @Route("/books", name="app_book")
 */
#[Route('/books', name: 'app_book')]
class BookController extends AbstractController
{
    /**
     * BookController constructor.
     * @param BookRepository $bookRepository
     */
    public function __construct(
        private readonly BookRepository $bookRepository,
        private readonly EntityManagerInterface $entityManager,
        private CommentRepository $commentRepository
    )
    {}

    #[Route('', name: '_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'books' => $this->bookRepository->findAll()
        ]);
    }

    #[Route('/{id}', name: '_show', methods: ['GET', 'POST'])]
    public function show(?Book $book, Request $request): Response
    {
        if (!$book) {
            $this->addFlash('error', 'Book not found');
            return $this->redirectToRoute('app_book_index');
        }

        $comments = $this->commentRepository->findByBook($book->getId());
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setBook($book)->setAuthor($this->getUser());

            $this->entityManager->persist($comment);
            $this->entityManager->flush();
            $this->addFlash('success', 'Your comment has been added');

            return $this->redirectToRoute('app_book_show', ['id' => $book->getId()]);
        }

        return $this->render('Book/show.html.twig', [
            'book' => $book,
            'comments' => $comments,
            'form' => $form
        ]);
    }

    #[Route('/create', name: '_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response|RedirectResponse
    {
        $book = new Book();

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($book);
            $this->entityManager->flush();
            $this->addFlash('success', 'Book created successfully');

            return $this->redirectToRoute('app_book_index');
        }

        return $this->render('book/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: '_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ?Book $book): Response|RedirectResponse
    {
        if (!$book) {
            $this->addFlash('error', 'Book not found');
            return $this->redirectToRoute('app_book_index');
        }

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($book);
            $this->entityManager->flush();
            $this->addFlash('success', 'Book updated successfully');

            return $this->redirectToRoute('app_book_index');
        }

        return $this->render('book/edit.html.twig', [
            'form' => $form,
            'book' => $book
        ]);
    }

    #[Route('/{id}/delete', name: '_delete', methods: ['POST'])]
    public function delete(Request $request, ?Book $book): RedirectResponse
    {
        if (!$book) {
            $this->addFlash('error', 'Book not found');
            return $this->redirectToRoute('app_book_index');
        }

        if ($this->isCsrfTokenValid('delete' . $book->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($book);
            $this->entityManager->flush();
            $this->addFlash('success', 'Book deleted successfully');

            return $this->redirectToRoute('app_book_index');
        }

        $this->addFlash('error', 'Invalid CSRF Token');

        return $this->redirectToRoute('app_book_index');
    }
}
