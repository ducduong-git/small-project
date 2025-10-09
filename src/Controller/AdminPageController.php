<?php

namespace App\Controller;

use App\Service\User\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminPageController extends AbstractController
{
    #[Route('/admin', name: 'admin_page')]
    public function index(Request $request): Response
    {
        if ($request->getSession()->get('user_permission') == 0) {
            return $this->redirectToRoute('app_homepage');
        }

        if (!$request->getSession()->get('user_id')) {
            return $this->redirectToRoute('login_page');
        }

        return $this->render('admin_page/pages/index.html.twig');
    }

    #[Route('/login', name: 'login_page')]
    public function login(Request $request): Response
    {
        if ($request->getSession()->get('user_id')) {
            return $this->redirectToRoute('admin_page');
        }

        return $this->render('admin_page/pages/login.html.twig');
    }

    #[Route('/submit-login', name: 'submit_login', methods: ['POST'])]
    public function submitLogin(Request $request, UserService $userService): Response
    {
        $existingUser = $userService->getUserByGmail($request->request->get('gmail'));

        if ($existingUser && password_verify($request->request->get('passw'), $existingUser->getPassw())) {
            $session = $request->getSession();
            $session->set('user_id', $existingUser->getId());
            $session->set('address', $existingUser->getAddress());
            $session->set('user_fname', $existingUser->getFname());
            $session->set('user_lname', $existingUser->getLname());
            $session->set('user_gmail', $existingUser->getGmail());
            $session->set('phone_num', $existingUser->getPhoneNum());
            $session->set('gender_role', $existingUser->getGenderRole());
            $session->set('user_permission', $existingUser->getPermission());

            $this->addFlash('success', 'Login successful!');
            return $this->redirectToRoute('admin_page');
        } else {

            $this->addFlash('error', 'Invalid Gmail or Password.');
            return $this->redirectToRoute('login_page');
        }
    }

    #[Route('/submit-logout', name: 'submit_logout')]
    public function submitLogout(Request $request, UserService $userService): Response
    {
        $session = $request->getSession();
        $session->remove('user_id');
        $session->remove('address');
        $session->remove('user_fname');
        $session->remove('user_lname');
        $session->remove('user_gmail');
        $session->remove('phone_num');
        $session->remove('gender_role');
        $session->remove('user_permission');

        $this->addFlash('success', 'Logout successful!');
        return $this->redirectToRoute('login_page');
    }

    #[Route('/register', name: 'register_page')]
    public function register(Request $request): Response
    {
        if ($request->getSession()->get('user_id')) {
            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('admin_page/pages/register.html.twig');
    }

    #[Route('/new-register', name: 'register_user', methods: ['POST'])]
    public function registerUser(Request $request, UserService $userService): Response
    {
        // Collect user data from the request
        $userData = [
            'fname' => $request->request->get('fname'),
            'lname' => $request->request->get('lname'),
            'gmail' => $request->request->get('gmail'),
            'passw' => $request->request->get('passw')
        ];

        if ($userService->getUserByGmail($userData['gmail'])) {
            $this->addFlash('error', 'Gmail already exists. Please use a different Gmail.');
            return $this->redirectToRoute('register_page');
        }

        if (empty($userData['fname']) || empty($userData['lname']) || empty($userData['gmail']) || empty($userData['passw'])) {
            $this->addFlash('error', 'All fields are required.');
            return $this->redirectToRoute('register_page');
        }

        $userService->createUser($userData);

        $this->addFlash('success', 'Register successful!');
        return $this->redirectToRoute('login_page');
    }
}
