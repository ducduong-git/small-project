<?php

namespace App\Controller\TraditionalAPI;

use App\Service\Category\CategoryService;
use App\Service\Product\ProductService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CategoryAPIController extends AbstractController
{
    private const _API_ROUTE = "//api/withoutapiplatform/";

    public function __construct(private CategoryService $categoryService, private NormalizerInterface $normalizerInterface) {}

    #[Route(path: self::_API_ROUTE . "category", name: "api_category")]
    public function provide(): Response
    {
        $categories = $this->categoryService->getAllCategory();

        $data = [
            "code" => 200,
            "success" => true,
            "data" => [
                "items" => $this->normalizerInterface->normalize($categories, ''),
            ]
        ];

        return new JsonResponse($data);
    }
}
