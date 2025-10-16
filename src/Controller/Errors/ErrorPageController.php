<?php

namespace App\Controller\Errors;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ErrorPageController extends AbstractController
{
    private const _NOT_EXIST_PAGE = "error/";

    #[Route('/error', name: '404_error')]
    public function index(): Response
    {
        return $this->render(self::_NOT_EXIST_PAGE . '404.html.twig');
    }
}
