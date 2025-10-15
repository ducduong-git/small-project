<?php

namespace App\Controller\Ajax;

use App\Service\Product\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;


class ProductAjaxController extends AbstractController
{

    #[Route('/ajax/get/products', name: 'ajax_get_product')]
    public function index(ProductService $productService): JsonResponse
    {
        $products = $productService->getProductWithCategory([], 'prod.*, cate.name AS category');

        return new JsonResponse([
            'status' => 'success',
            'data' => $products,
        ]);
    }
}
