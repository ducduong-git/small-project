<?php

namespace App\Controller\UserController;

use App\Service\User\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class UserPageController extends AbstractController
{
    private const _PREFIX_PAGE_ADMIN_REDER = "admin_page/pages/user_pages/";
    private const _PREFIX_ADMIN = "//admin/";

    #[Route(self::_PREFIX_ADMIN . "list-user", "admin_user")]
    public function index(): Response
    {
        return $this->render(self::_PREFIX_PAGE_ADMIN_REDER . "index.html.twig");
    }
}
