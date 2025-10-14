<?php

namespace App\ApiResource\ProductAPI;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\QueryParameter;

#[ApiResource(
    operations: [
        new GetCollection(
            shortName: "product",
            uriTemplate: '/product-data',  // Your endpoint, e.g., /api/custom-data
            controller: 'App\Controller\API\ProductDataController::get',
            read: false,  // Skip automatic entity read/listener if not needed
            parameters: [
                'category' => new QueryParameter(
                    schema: ['type' => 'integer'],
                    description: 'Filter products by category'
                ),
                'search name' => new QueryParameter(
                    schema: ['type' => 'string'],
                    description: 'Search name of products'
                ),
            ],
            paginationEnabled: true,
        ),
        new Get(
            shortName: 'product detail',
            uriTemplate: '/product-data/{id}',
            controller: 'App\Controller\API\ProductDataController::detail',
            read: false,  // Skip automatic entity read/listener if not needed
        )
    ]
)]
class ProductData
{
    // No properties needed! This isn't persisted.
    // If you want to return structured data, add public properties here (e.g., public string $result;)
}
