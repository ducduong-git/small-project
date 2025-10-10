<?php

namespace App\Controller;

use App\Service\Product\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class ProductPageController extends AbstractController {
    
    #[Route('/admin/products', name: 'admin_product')]
    public function index(ProductService $productService) :Response {
        $products = $productService->getAllProduct();
        return $this->render('admin_page/pages/product_pages/index.html.twig', ['products' => $products]);
    }
}