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
    private const _PRODUCT_PATH = "admin_page/pages/product_pages/";
    private const _PREFIX = "//admin/";

    #[Route(self::_PREFIX . 'products', name: 'admin_product')]
    public function index(): Response
    {
        return $this->render(self::_PRODUCT_PATH . 'index.html.twig');
    }

    #[Route(self::_PREFIX . 'new-product', name: 'new_product')]
    public function addMoreProductPage(CategoryService $categoryService): Response
    {
        $categories = $categoryService->getExistCategory();
        return $this->render(self::_PRODUCT_PATH . 'add.html.twig', ['categories' => $categories]);
    }

    #[Route(self::_PREFIX . 'submit-product', name: 'add_product', methods: ['POST'])]
    public function addMoreProduct(Request $request, ProductService $productService): Response
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

    #[Route(self::_PREFIX . 'edit-product/{id}', name: 'edit_product')]
    public function editProductPage(CategoryService $categoryService, ProductService $productService, int $id): Response
    {
        $categories = $categoryService->getAllCategory();

        $currentProduct = $productService->getSingleProduct($id);

        $beautiProductValue = [
            'id' => $currentProduct->getId(),
            'name' => $currentProduct->getName(),
            'qty' => $currentProduct->getQty(),
            'price' => $currentProduct->getPrice(),
            'status' => $currentProduct->getStatus(),
            'cateId' => $currentProduct->getCateId(),
            'mainImg' => $currentProduct->getMainImg(),
            'listImg' => json_decode($currentProduct->getListImg())
        ];

        return $this->render(self::_PRODUCT_PATH . 'edit.html.twig', ['categories' => $categories, 'editProduct' => $beautiProductValue]);
    }

    #[Route(self::_PREFIX . 'update-product', name: 'update_product', methods: ['POST'])]
    public function updateProduct(ProductService $productService, Request $request): Response
    {
        $product = $productService->checkFormRequest($request);
        
        $referer = $request->headers->get('referer');

        if (!isset($product) || empty($product)) {
            $this->addFlash('error', 'Invalid name of category');
            return $this->redirect($referer);
        }

        $productService->updateProduct( $request);

        return $this->redirectToRoute('admin_product');
    }

    // #[Route(self::_PREFIX . 'soft-delete-categories', name: 'soft_delete_categories', methods: ['DELETE'])]
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
