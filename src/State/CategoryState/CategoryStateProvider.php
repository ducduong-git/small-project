<?php

namespace App\State\CategoryState;

use ApiPlatform\State\ProviderInterface;
use ApiPlatform\Metadata\Operation;
use App\Service\Category\CategoryService;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CategoryStateProvider implements ProviderInterface
{
    public function __construct(private HttpClientInterface $httpClient, private CategoryService $categoryService,
        private NormalizerInterface $normalizer) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        // try {
        //     // Call your internal API
        //     $response = $this->httpClient->request(
        //         'GET',
        //         'http://localhost:8080/api/withoutapiplatform/category'  // ✅ Adjust port
        //     );

        //     $categoryData = $response->toArray();

        //     return $categoryData;
        // } catch (\Exception $e) {
        //     // Handle errors gracefully
        //     return [
        //         'error' => $e->getMessage()
        //     ];
        // }

        // Get categories directly from service
        $categories = $this->categoryService->getAllCategory();

        return [
            "code" => 200,
            "success" => true,
            "data" => [
                "items" => $this->normalizer->normalize($categories, ''),
            ]
        ];

        // Tạo entity CategoryEntity từ dữ liệu bên ngoài
        // $category = new CategoryEntity();
        // $category->setName($categoryData['name'] ?? 'Unknown');

        // return $categoryData;
    }
}
