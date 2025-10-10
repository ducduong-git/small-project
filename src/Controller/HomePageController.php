<?php

namespace App\Controller;

use App\Service\Category\CategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class HomePageController extends AbstractController {
    
    #[Route('/', name: 'app_homepage')]
    public function index(CategoryService $categoryService) :Response {
        $category = $categoryService->getExistCategory();
        return $this->render('shopee_page/pages/homepage.html.twig', ['category' => $category]);
    }
}