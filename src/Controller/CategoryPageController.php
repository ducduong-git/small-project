<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class CategoryPageController extends AbstractController
{

    #[Route('/admin/categories', name: 'admin_categories')]
    public function index(): Response
    {
        return $this->render('admin_page/pages/category_pages/index.html.twig');
    }
}
