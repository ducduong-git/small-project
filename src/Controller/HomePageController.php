<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class HomePageController extends AbstractController {
    
    #[Route('/', name: 'app_homepage')]
    public function index() :Response {
        return $this->render('shopee_page/pages/homepage.html.twig');
    }
}