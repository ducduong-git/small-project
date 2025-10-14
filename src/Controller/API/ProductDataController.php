<?php

namespace App\Controller\API;

use App\Service\Product\ProductService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[AsController]
class ProductDataController
{
    public function __construct(private ProductService $productService, private NormalizerInterface $normalizer) {}

    public function get(Request $request): JsonResponse
    {
        $filter = [
            "page" => $request->query->get("page"),
            "filter" => $request->query->get("category"),
            "search_name" => $request->query->get("search_name"),
        ];

        $products = $this->productService->getProducts($filter);

        // Your custom logic here (not tied to an entity)
        // Example: Call an external service, compute something, etc.
        $data = [
            "code" => 200,
            "success" => true,
            "data" => [
                "items" => $products
            ]
        ];

        return new JsonResponse($data);
    }

    public function detail(int $id): JsonResponse
    {
        $product = $this->productService->getSingleProduct($id);

        $productData = [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'qty' => $product->getQty(),
            'mainImg' => $product->getMainImg(),
            'listImg' => json_decode($product->getListImg()), // Already decode JSON
            'price' => $product->getPrice(),
            'status' => $product->getStatus(),
            'cateId' => $product->getCateId(),
            'createdAt' => $product->getCreatedAt()?->format('Y-m-d H:i:s'),
            'updatedAt' => $product->getUpdatedAt()?->format('Y-m-d H:i:s'),
        ];

        $data = [
            "code" => 200,
            "success" => true,
            "data" => [
                "items" =>  $productData
            ]
        ];

        return new JsonResponse($data);
    }
}
