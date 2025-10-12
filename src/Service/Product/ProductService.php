<?php

namespace App\Service\Product;

use App\Entity\ProductEntity;
use App\Repository\ProductEntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ProductService
{
    private ProductEntityRepository $productRepository;
    private string $uploadDir;

    public function __construct(private HtmlSanitizerInterface $sanitizer, ProductEntityRepository $productRepository, ParameterBagInterface $params)
    {
        $this->productRepository = $productRepository;
        $this->uploadDir = $params->get('kernel.project_dir') . '/public/uploads/images/products';
    }

    public function checkFormRequest(Request $request): ?ProductEntity
    {
        $nameProduct = $this->sanitizer->sanitize(trim($request->request->get('name')));
        $categoryId = $request->request->get('cate_id');

        if (!isset($nameProduct) || empty($nameProduct) || !isset($nameProduct) || empty($nameProduct) || $categoryId == 0) {
            return null;
        }

        $product = new ProductEntity();
        $product->setName($nameProduct)->setQty($request->request->get('qty'))->setStatus($request->request->get('status'))->setCateId($categoryId)->setPrice($request->request->get('price'));

        return $product;
    }


    public function getAllProduct(): ?array
    {
        return $this->productRepository->getAllProduct();
    }

    public function getExistProduct(): ?array
    {
        return $this->productRepository->getExistProduct();
    }

    private function addImage($file, $name): String
    {
        if (!$file) {
            return '';
        }

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0775, true);
        }

        $newFilename = uniqid('', true) . '_' . $name . '.' . $file->guessExtension();
        //Make beauty name
        $newFilename = str_replace(' ', '_', $newFilename);

        $file->move($this->uploadDir, $newFilename);

        return $newFilename;
    }

    public function addNewProduct(ProductEntity $productEntity, Request $request): ?ProductEntity
    {
        $file = $request->files->get('main_image');
        $name = $request->request->get('name');

        if (!$file) {
            return null;
        }

        $file_name = $this->addImage($file, $name);

        if (!$file_name) {
            return null;
        }

        $productEntity->setMainImg($file_name);

        $this->productRepository->addNewProduct($productEntity);

        return $productEntity;
    }
}
