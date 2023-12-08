<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class for Author CRUD
 * @package App\Controller
 * @Route("/authors", name="app_author")
 */
#[Route('/authors', name: 'app_author')]
class AuthorController extends AbstractController
{
    /**
     * AuthorController constructor.
     * @param AuthorRepository $authorRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        private AuthorRepository $authorRepository,
        private EntityManagerInterface $entityManager
    )
    {}

    /**
     * @Route('', name: '_index') : to get all Authors
     * @return Response
     */
    #[Route('', name: '_index')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'authors' => $this->authorRepository->findAll()
        ]);
    }

    /**
     * @Route('/{id}', name: '_show', methods: ['GET']) : to get a specific Author
     * @param Author|null $author
     * @return Response
     */
    #[Route('/{id}', name: '_show', methods: ['GET'])] // GET : to get a specific Author
    public function show(?Author $author): Response
    {
        if (!$author) {
            $this->addFlash('error', 'Author not found');
            return $this->redirectToRoute('app_author_index');
        }

        return $this->render('Author/show.html.twig', [
            'author' => $author
        ]);
    }

    /**
     * @Route('/create', name: '_create') : to create a Author
     * @param Request $request
     * @return Response
     */
    #[Route('/create', name: '_create')]
    public function create(Request $request): Response
    {
        $author = new Author();

        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($author);
            $this->entityManager->flush();
            $this->addFlash('success', 'Author created successfully');

            return $this->redirectToRoute('app_author_index');
        }

        return $this->render('Author/create.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route('/{id}/edit', name: '_edit') : to edit a Author
     * @param Request $request
     * @param Author|null $author
     * @return Response
     */
    #[Route('/{id}/edit', name: '_edit')]
    public function edit(Request $request, ?Author $author): Response
    {
        if (!$author) {
            $this->addFlash('error', 'Author not found');
            return $this->redirectToRoute('app_author_index');
        }

        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($author);
            $this->entityManager->flush();
            $this->addFlash('success', 'Author updated successfully');

            return $this->redirectToRoute('app_author_index');
        }

        return $this->render('Author/edit.html.twig', [
            'form' => $form,
            'author' => $author
        ]);
    }

    /**
     * @Route('/{id}/delete', name: '_delete', methods: ['POST']) : to delete a Author
     * @param Request $request
     * @param Author|null $author
     * @return Response|RedirectResponse
     */
    #[Route('/{id}/delete', name: '_delete', methods: ['POST'])] // POST : to delete a Author
    public function delete(Request $request, ?Author $author): Response|RedirectResponse
    {
        if (!$author) {
            $this->addFlash('error', 'Author not found');
            return $this->redirectToRoute('app_author_index');
        }

        if ($this->isCsrfTokenValid('delete' . $author->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($author);
            $this->entityManager->flush();
            $this->addFlash('success', 'Author deleted successfully');

            return $this->redirectToRoute('app_author_index');
        }

        $this->addFlash('error', 'Invalid CSRF Token');
        return $this->redirectToRoute('app_author_index');
    }
}
