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
        $providerData = $request->attributes->get('data');

        // Get through parrameter they want to filter like page, name product, status 
        $filter = [
            "page" => $request->query->get("page"),
            "filter" => $request->query->get("category"),
            "search_name" => $request->query->get("search_name"),
            "status" => $request->query->get("status"),
        ];

        $products = $this->productService->getProducts($filter);
        $products = $this->normalizer->normalize($products, '');

        $items = array_merge($products, $providerData['data']);
        // Your custom logic here (not tied to an entity)
        // Example: Call an external service, compute something, etc.
        $data = [
            "code" => 200,
            "success" => true,
            "data" => [
                "items" => $items
            ]
        ];

        return new JsonResponse($data);
    }

    public function detail(int $id): JsonResponse
    {
        $product = $this->productService->getSingleProduct($id);

        $newProduct = $this->productService->getProductWithCategoryDTO($id);
        $newProduct = $this->normalizer->normalize($newProduct, '');

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
                "items" =>  $productData,
                "newProduct" => $newProduct
            ]
        ];

        return new JsonResponse($data);
    }

    public function add(Request $request): JsonResponse
    {
        dd($request->request->all());

        $data = [
            "code" => 200,
            "success" => true,
            "data" => [
                "items" => 'success'
            ]
        ];

        return new JsonResponse($data);
    }
}
