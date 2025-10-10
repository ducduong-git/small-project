<?php

namespace App\Service\Category;

use App\Entity\CategoryEntity;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerInterface;

class CategoryService
{
    private CategoryRepository $categoryRepository;

    public function __construct(private HtmlSanitizerInterface $sanitizer, CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function checkFormRequest(Request $request): ?CategoryEntity
    {
        $name_category = $this->sanitizer->sanitize(trim($request->request->get('name')));

        if (!isset($name_category) || empty($name_category)) {
            return null;
        }

        $category = new CategoryEntity();
        $category->setName($name_category)->setStatus($request->request->get('status'))->setParentId($request->request->get('parentId') ?? 0);

        return $category;
    }

    public function getAllCategory(): ?array
    {
        return $this->categoryRepository->getAllCategory();
    }

    public function getExistCategory(): ?array
    {
        return $this->categoryRepository->getExistCategory();
    }

    public function addNewCategory(CategoryEntity $categoryEntity): CategoryEntity
    {
        return $this->categoryRepository->addNewCategory($categoryEntity);
    }

    public function getSingleCategory(int $id): ?CategoryEntity
    {
        return $this->categoryRepository->findOneCategory($id);
    }

    public function updateCategory(int $id, CategoryEntity $categoryEntity): ?CategoryEntity
    {
        $category = $this->categoryRepository->findOneCategory($id);

        $name = $category->getName();
        $parentId = $category->getParentId();
        $status = $category->getStatus();

        if ($name != $categoryEntity->getName()) {
            $name = $categoryEntity->getName();
        }

        if ($parentId != $categoryEntity->getParentId()) {
            $parentId = $categoryEntity->getParentId();
        }

        if ($status != $categoryEntity->getStatus()) {
            $status = $categoryEntity->getStatus();
        }

        return $this->categoryRepository->updateCategory($id, $name, $status, $parentId);
    }

    public function softDeleteCategory(int $id)
    {
        return $this->categoryRepository->softDeleteCategory($id);
    }
}
