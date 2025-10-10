<?php

namespace App\Controller;

use App\Entity\CategoryEntity;
use App\Service\Category\CategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class CategoryPageController extends AbstractController
{

    #[Route('/admin/categories', name: 'admin_categories')]
    public function index(CategoryService $categoryService): Response
    {
        $categories = $categoryService->getAllCategory();
        return $this->render('admin_page/pages/category_pages/index.html.twig', ['categories' => $categories]);
    }

    #[Route('/admin/new-categories', name: 'new_categories')]
    public function addMoreCategoryPage(CategoryService $categoryService): Response
    {
        $categories = $categoryService->getAllCategory();
        return $this->render('admin_page/pages/category_pages/add.html.twig', ['categories' => $categories]);
    }

    #[Route('/admin/submit-category', name: 'add_category', methods: ['POST'])]
    public function addMoreCategory(Request $request, CategoryService $categoryService): Response
    {
        $category = $categoryService->checkFormRequest($request);

        $referer = $request->headers->get('referer');

        if (!isset($category) || empty($category)) {
            $this->addFlash('error', 'Invalid name of category');
            return $this->redirect($referer);
        }

        $categoryService->addNewCategory($category);

        return $this->redirectToRoute('admin_categories');
    }

    #[Route('/admin/edit-categories/{id}', name: 'edit_categories')]
    public function editCategoryPage(CategoryService $categoryService, int $id): Response
    {
        $categories = $categoryService->getAllCategory();
        $current_category = $categoryService->getSingleCategory($id);

        return $this->render('admin_page/pages/category_pages/edit.html.twig', ['categories' => $categories, 'editCategory' => $current_category]);
    }

    #[Route('/admin/update-categories', name: 'update_categories', methods: ['PUT'])]
    public function updateCategoryPage(CategoryService $categoryService, Request $request): Response
    {
        $category = $categoryService->checkFormRequest($request);

        $referer = $request->headers->get('referer');

        if (!isset($category) || empty($category)) {
            $this->addFlash('error', 'Invalid name of category');
            return $this->redirect($referer);
        }

        $categoryService->updateCategory($request->request->get('idCategory'), $category);

        return $this->redirectToRoute('admin_categories');
    }

    #[Route('/admin/soft-delete-categories', name: 'soft_delete_categories', methods: ['DELETE'])]
    public function softDeleteCategory(CategoryService $categoryService, Request $request): Response
    {
        $exitsCategory = $categoryService->getSingleCategory($request->request->get('idCategory'));

        if (!isset($exitsCategory) || empty($exitsCategory)) {
            $referer = $request->headers->get('referer');
            $this->addFlash('error', 'Invalid name of category');
            return $this->redirect($referer);
        }

        $categoryService->softDeleteCategory($exitsCategory->getId());

        $this->addFlash('success', 'Remove category complete');
        return $this->redirectToRoute('admin_categories');
    }
}
