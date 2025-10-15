<?php

namespace App\Service\Product;

use App\Entity\ProductEntity;
use App\Repository\ProductEntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ProductService
{
    private string $uploadDir;

    public function __construct(private HtmlSanitizerInterface $sanitizer, private ProductEntityRepository $productRepository, ParameterBagInterface $params)
    {
        $this->uploadDir = $params->get('kernel.project_dir') . '/public/uploads/images/products';
    }

    public function checkFormRequest(Request $request): ?ProductEntity
    {
        $nameProduct = $this->sanitizer->sanitize(trim($request->request->get('name')));
        $categoryId = $request->request->get('cate_id');

        if (!isset($nameProduct) || empty($nameProduct) || !isset($categoryId) || empty($categoryId) || $categoryId == 0) {
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

    public function getProductWithCategory(array $filter = [], string $select = '*'): ?array
    {
        return $this->productRepository->getProductWithCategory($filter, $select);
    }

    public function getExistProduct(): ?array
    {
        return $this->productRepository->getExistProduct();
    }

    public function filterProduct(array $parram): ?array
    {
        $limit = $offset = null;

        if (isset($parram['page'])) {
            $page = $parram['page'];
            $limit = 12;
            $offset =  $page > 1 ? $page - 1 * $limit : 0;
        }

        $filter['filter'] = isset($parram['category']) ? $parram['category'] : null;

        $price = (isset($parram['price']) && $parram['price'] == 'lowToHigh') ? ['price' => 'ASC'] : ['price' => 'DESC'];

        $products = $this->productRepository->findProduct($filter, $price, $limit, $offset);

        return $products;
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

    private function removeImage($file): String
    {
        $imagePath = $this->uploadDir . '/' . $file;

        if (file_exists($imagePath)) {
            unlink($imagePath); // Deletes the file
        }

        return '';
    }

    public function addNewProduct(ProductEntity $productEntity, Request $request): ?ProductEntity
    {
        $file = $request->files->get('main_image');
        $listFile = $request->files->get('list_img');
        $name = $request->request->get('name');

        if (!$file) {
            return null;
        }

        $fileName = $this->addImage($file, $name);

        if (!$fileName) {
            return null;
        }

        $listImageName = [];

        foreach ($listFile as $file) {
            $listImageName[] = $this->addImage($file, $name);
        }

        $jsonListImageName = json_encode($listImageName);

        $productEntity->setMainImg($fileName)->setListImg($jsonListImageName);

        $this->productRepository->addNewProduct($productEntity);

        return $productEntity;
    }

    public function getSingleProduct(int $id): ?ProductEntity
    {
        return $this->productRepository->findOneProduct($id);
    }

    public function updateProduct(Request $request): ?ProductEntity
    {
        $file = $request->files->get('main_image');
        $listFile = $request->files->get('list_img');
        $name = $request->request->get('name');

        $productEntity = $this->productRepository->findOneProduct($request->request->get('prodId'));

        if ($file) {
            $this->removeImage($productEntity->getMainImg());

            $fileName = $this->addImage($file, $name);

            $productEntity->setMainImg($fileName);
        }

        if ($listFile) {
            $listOldImage = json_decode($productEntity->getListImg());

            foreach ($listOldImage as $imageFile) {
                $this->removeImage($imageFile);
            }

            $listImageName = [];

            foreach ($listFile as $file) {
                $listImageName[] = $this->addImage($file, $name);
            }

            $jsonListImageName = json_encode($listImageName);

            $productEntity->setListImg($jsonListImageName);
        }

        $productEntity->setName($name)
            ->setQty($request->request->get('qty'))
            ->setStatus($request->request->get('status'))
            ->setCateId($request->request->get('cate_id'))
            ->setPrice($request->request->get('price'));

        $this->productRepository->update($productEntity);

        return $productEntity;
    }

    public function getProducts(array $filter): ?array
    {
        $products = $this->productRepository->findProduct($filter);

        return $products;
    }

    public function getProductWithCategoryDTO(int $id): ?array
    {
        $filter = ['id' => $id];
        return $this->productRepository->getProductWithCategoryDTO($filter);
    }
}
