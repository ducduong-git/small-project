<?php

namespace App\Module\Product\Controller;


use App\Module\Category\Service\CategoryService;
use App\Module\Product\Service\ProductService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class ProductPageController extends AbstractController
{
    private const _PREFIX_PAGE_ADMIN_REDER = "@Product/AdminPages/";
    private const _PREFIX_ADMIN            = "//admin/";
    private const _PREFIX_PAGE_PRODUCT     = "shopee_page/pages/product_pages/";


    #[Route(self::_PREFIX_ADMIN . 'products', name: 'admin_product')]
    public function index(): Response
    {
        return $this->render(self::_PREFIX_PAGE_ADMIN_REDER . 'index.html.twig');
    }

    #[Route(self::_PREFIX_ADMIN . 'new-product', name: 'new_product')]
    public function addMoreProductPage(CategoryService $categoryService): Response
    {
        $categories = $categoryService->getExistCategory();
        return $this->render(self::_PREFIX_PAGE_ADMIN_REDER . 'add.html.twig', ['categories' => $categories]);
    }

    #[Route(self::_PREFIX_ADMIN . 'edit-product/{id}', name: 'edit_product')]
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

        return $this->render(self::_PREFIX_PAGE_ADMIN_REDER . 'edit.html.twig', ['categories' => $categories, 'editProduct' => $beautiProductValue]);
    }

    // #[Route(self::_PREFIX_ADMIN . 'soft-delete-categories', name: 'soft_delete_categories', methods: ['DELETE'])]
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

    #[Route('product-detail', name: 'app_product_detail')]
    public function detail():Response {
        return $this->render(self::_PREFIX_PAGE_PRODUCT . 'productDetail.html.twig');
    }
}
