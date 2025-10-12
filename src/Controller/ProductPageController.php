<?php

namespace App\Controller;

use App\Service\Category\CategoryService;
use App\Service\Product\ProductService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class ProductPageController extends AbstractController
{

    #[Route('/admin/products', name: 'admin_product')]
    public function index(): Response
    {
        return $this->render('admin_page/pages/product_pages/index.html.twig');
    }

    #[Route('/admin/new-product', name: 'new_product')]
    public function addMoreCategoryPage(CategoryService $categoryService): Response
    {
        $categories = $categoryService->getExistCategory();
        return $this->render('admin_page/pages/product_pages/add.html.twig', ['categories' => $categories]);
    }

    #[Route('/admin/submit-product', name: 'add_product', methods: ['POST'])]
    public function addMoreCategory(Request $request, ProductService $productService): Response
    {
        $product = $productService->checkFormRequest($request);

        $referer = $request->headers->get('referer');

        if (!isset($product) || empty($product)) {
            $this->addFlash('error', 'Invalid name of product');
            return $this->redirect($referer);
        }

        $status = $productService->addNewProduct($product, $request);

        if (!isset($status) || empty($status)) {
            $this->addFlash('error', 'Invalid product');
            return $this->redirect($referer);
        }

        return $this->redirectToRoute('admin_product');
    }

    // #[Route('/admin/edit-categories/{id}', name: 'edit_categories')]
    // public function editCategoryPage(CategoryService $categoryService, int $id): Response
    // {
    //     $categories = $categoryService->getAllCategory();
    //     $current_category = $categoryService->getSingleCategory($id);

    //     return $this->render('admin_page/pages/category_pages/edit.html.twig', ['categories' => $categories, 'editCategory' => $current_category]);
    // }

    // #[Route('/admin/update-categories', name: 'update_categories', methods: ['PUT'])]
    // public function updateCategoryPage(CategoryService $categoryService, Request $request): Response
    // {
    //     $category = $categoryService->checkFormRequest($request);

    //     $referer = $request->headers->get('referer');

    //     if (!isset($category) || empty($category)) {
    //         $this->addFlash('error', 'Invalid name of category');
    //         return $this->redirect($referer);
    //     }

    //     $categoryService->updateCategory($request->request->get('idCategory'), $category);

    //     return $this->redirectToRoute('admin_categories');
    // }

    // #[Route('/admin/soft-delete-categories', name: 'soft_delete_categories', methods: ['DELETE'])]
    // public function softDeleteCategory(CategoryService $categoryService, Request $request): Response
    // {
    //     $exitsCategory = $categoryService->getSingleCategory($request->request->get('idCategory'));

    //     if (!isset($exitsCategory) || empty($exitsCategory)) {
    //         $referer = $request->headers->get('referer');
    //         $this->addFlash('error', 'Invalid name of category');
    //         return $this->redirect($referer);
    //     }

    //     $categoryService->softDeleteCategory($exitsCategory->getId());

    //     $this->addFlash('success', 'Remove category complete');
    //     return $this->redirectToRoute('admin_categories');
    // }
}
