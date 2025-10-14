<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminPageController extends AbstractController
{
    private const _PREFIX_PAGE_ADMIN_REDER = "admin_page/pages/";

    #[Route('/admin', name: 'admin_page')]
    public function index(Request $request): Response
    {
        if ($request->getSession()->get('user_permission') == 0) {
            return $this->redirectToRoute('app_homepage');
        }

        if (!$request->getSession()->get('user_id')) {
            return $this->redirectToRoute('login_page');
        }

        return $this->render(self::_PREFIX_PAGE_ADMIN_REDER . 'index.html.twig');
    }
}
