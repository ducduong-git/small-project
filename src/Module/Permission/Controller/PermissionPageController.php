<?php

// namespace App\Controller\Module\Controller;
namespace App\Module\Permission\Controller;

use App\Service\User\AuthenticationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PermissionPageController extends AbstractController
{
    private const _PREFIX_PAGE_ADMIN_REDER = "admin_page/pages/";
    private const _PREFIX_ADMIN = "//admin/";

    #[Route(self::_PREFIX_ADMIN .'permission', name: 'admin_permission')]
    public function index(Request $request): Response
    {
      
        return $this->render('@Permission/index.html.twig');
    }
}
