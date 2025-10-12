<?php

namespace App\Controller;

use App\Service\Category\CategoryService;
use App\Service\Product\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class HomePageController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function index(CategoryService $categoryService, ProductService $productService): Response
    {
        $categories = $categoryService->getExistCategory();
        $products = $productService->getExistProduct();

        $data = [
            'categories' => $categories,
            'products' => $products
        ];

        return $this->render('shopee_page/pages/homepage.html.twig', $data);
    }
}
