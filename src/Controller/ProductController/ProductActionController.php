<?php

namespace App\Controller\ProductController;

use App\Service\Category\CategoryService;
use App\Service\Product\ProductService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class ProductActionController extends AbstractController
{
    private const _PREFIX_ADMIN = "//admin/";

    #[Route(self::_PREFIX_ADMIN . 'submit-product', name: 'add_product', methods: ['POST'])]
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

    #[Route(self::_PREFIX_ADMIN . 'update-product', name: 'update_product', methods: ['POST'])]
    public function updateProduct(ProductService $productService, Request $request): Response
    {
        $product = $productService->checkFormRequest($request);

        $referer = $request->headers->get('referer');

        if (!isset($product) || empty($product)) {
            $this->addFlash('error', 'Invalid name of category');
            return $this->redirect($referer);
        }

        $productService->updateProduct($request);

        return $this->redirectToRoute('admin_product');
    }
}
