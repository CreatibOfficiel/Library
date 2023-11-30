<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categories', name: 'app_category')]
class CategoryController extends AbstractController
{
    public function __construct(
        private CategoryRepository $categoryRepository,
        private EntityManagerInterface $entityManager
    )
    {}

    #[Route('', name: '_index', methods: ['GET'])] // GET : to get all categories
    public function index(): Response
    {
        return $this->render('Category/index.html.twig', [
            'categories' => $this->categoryRepository->findAll()
        ]);
    }

    #[Route('/create', name: '_create', methods: ['GET', 'POST'])] // POST : to create a new Category
    public function create(Request $request): Response|RedirectResponse
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($category);
            $this->entityManager->flush();
            $this->addFlash('success', 'Category created successfully');

            return $this->redirectToRoute('app_category_index');
        }

        return $this->render('Category/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: '_edit', methods: ['GET', 'POST'])] // POST : to edit a Category
    public function edit(Request $request, ?Category $category): Response|RedirectResponse
    {
        if (!$category) {
            $this->addFlash('error', 'Category not found');
            return $this->redirectToRoute('app_category_index');
        }

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { // if the form is submitted and valid
            $this->entityManager->persist($category);
            $this->entityManager->flush();
            $this->addFlash('success', 'Category updated successfully');

            return $this->redirectToRoute('app_category_index');
        }

        return $this->render('Category/edit.html.twig', [
            'form' => $form,
            'category' => $category
        ]);
    }

    #[Route('/{id}/delete', name: '_delete', methods: ['POST'])] // POST : to delete a Category
    public function delete(Request $request, ?Category $category): Response|RedirectResponse
    {
        if (!$category) {
            $this->addFlash('error', 'Category not found');
            return $this->redirectToRoute('app_category_index');
        }

        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($category);
            $this->entityManager->flush();
            $this->addFlash('success', 'Category deleted successfully');

            return $this->redirectToRoute('app_category_index');
        }

        $this->addFlash('error', 'Invalid CSRF Token');
        return $this->redirectToRoute('app_category_index');
    }

}
