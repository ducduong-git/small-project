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
        $products = $productService->getAllProduct();

        // Convert entities to arrays
        $data = array_map(function ($product) {
            return [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'qty' => $product->getQty(),
                'price' => $product->getPrice(),
                'status' => $product->getStatus(),
                'cateId' => $product->getCateId(),
                'mainImg' => $product->getMainImg(),
            ];
        }, $products);

        return new JsonResponse([
            'status' => 'success',
            'data' => $data,
        ]);
    }
}
