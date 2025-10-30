<?php

namespace App\ApiResource\ProductAPI;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\QueryParameter;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\State\CategoryState\CategoryStateProvider; 

class ProductData
{
    // No properties needed! This isn't persisted.
    // If you want to return structured data, add public properties here (e.g., public string $result;)
    public ?string $name = null;
    public ?int $category = null;
    public ?int $qty = null;
    public ?int $status = null;
    public ?float $price = null;
    public ?UploadedFile $mainImg = null;
}
