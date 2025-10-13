<?php

namespace App\Controller\LoginController;

use App\Service\User\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LoginPageController extends AbstractController
{
    #[Route('/login', name: 'login_page')]
    public function login(Request $request): Response
    {
        if ($request->getSession()->get('user_id')) {
            return $this->redirectToRoute('admin_page');
        }

        return $this->render('admin_page/pages/login.html.twig');
    }

    #[Route('/register', name: 'register_page')]
    public function register(Request $request): Response
    {
        if ($request->getSession()->get('user_id')) {
            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('admin_page/pages/register.html.twig');
    }
}
