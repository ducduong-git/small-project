<?php

namespace App\Controller\LoginController;

use App\Service\AuthenticationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AuthenticationController extends AbstractController
{
    private $authenticationService;

    public function __construct(AuthenticationService $authenticationService) {
        $this->authenticationService = $authenticationService;
    }

    #[Route('/submit-login', name: 'submit_login', methods: ['POST'])]
    public function submitLogin(Request $request): Response
    {
        $existingUser = $this->authenticationService->login($request->request->get('gmail'), $request->request->get('passw'), $request);

        if ($existingUser) {
            $this->addFlash('success', 'Login successful!');
            return $this->redirectToRoute('admin_page');
        }

        $this->addFlash('error', 'Invalid Gmail or Password.');

        return $this->redirectToRoute('login_page');
    }

    #[Route('/submit-logout', name: 'submit_logout')]
    public function submitLogout(Request $request): Response
    {
        $this->authenticationService->logout($request);
        $this->addFlash('success', 'Logout successful!');

        return $this->redirectToRoute('login_page');
    }

    #[Route('/new-register', name: 'register_user', methods: ['POST'])]
    public function registerUser(Request $request): Response
    {
        $status = $this->authenticationService->register($request);

        $this->addFlash($status['status'], $status['msg']);

        if ($status['status'] != 'success') {
            return $this->redirectToRoute('register_page');
        }

        return $this->redirectToRoute('login_page');
    }
}
