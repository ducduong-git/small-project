<?php

namespace App\Service\Product;

use App\Entity\ProductEntity;
use App\Repository\ProductEntityRepository;

class ProductService
{

    private ProductEntityRepository $productRepository;

    public function __construct(ProductEntityRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAllProduct(): ?array
    {
        return $this->productRepository->getAllProduct();
    }
}
