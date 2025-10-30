<?php

namespace App\Module\User\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class UserPageController extends AbstractController
{
    private const _PREFIX_PAGE_ADMIN_REDER = "@User/AdminPages/";
    private const _PREFIX_ADMIN = "//admin/";

    #[Route(self::_PREFIX_ADMIN . "list-user", "admin_user")]
    public function index(): Response
    {
        return $this->render(self::_PREFIX_PAGE_ADMIN_REDER . "index.html.twig");
    }
}
