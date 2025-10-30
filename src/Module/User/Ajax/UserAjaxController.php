<?php

namespace App\Module\User\Ajax;

use App\Module\User\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;


class UserAjaxController extends AbstractController
{

    #[Route('/ajax/get/user', name: 'ajax_get_user')]
    public function index(UserService $userService): JsonResponse
    {
        $users = $userService->getAll();

        // Convert entities to arrays
        $data = array_map(function ($user) {
            return [
                'id' => $user->getId(),
                'name' => $user->getFname() . ' ' . $user->getLname(),
                'gmail' => $user->getGmail(),
                'gender' => empty($user->getGenderRole()) ? 'Unidentified' : ($user->getGenderRole() == 0 ? 'Female' : 'Male'),
                'status' => $user->getStatus() != 0 ? 'Active' : 'In Active',
                'phone' => $user->getPhoneNum(),
                'permission' => $user->getPermission(),
            ];
        }, $users);

        return new JsonResponse([
            'status' => 'success',
            'data' => $data,
        ]);
    }
}
