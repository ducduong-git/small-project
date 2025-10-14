<?php

namespace App\Service\User;

use App\Entity\UserEntity;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;

class AuthenticationService
{
    private const _DEFAULT_PERMISSION = 0;
    private const _DEFAULT_STATUS = 1;

    public function __construct(private UserRepository $userRepository)
    {
    }

    public function login(string $username, string $password, Request $request): ?UserEntity
    {
        $user = $this->userRepository->findByGmail($username);

        if ($user && password_verify($password, $user->getPassw())) {
            $session = $request->getSession();
            $session->set('user_id', $user->getId());
            $session->set('address', $user->getAddress());
            $session->set('user_fname', $user->getFname());
            $session->set('user_lname', $user->getLname());
            $session->set('user_gmail', $user->getGmail());
            $session->set('phone_num', $user->getPhoneNum());
            $session->set('gender_role', $user->getGenderRole());
            $session->set('user_permission', $user->getPermission());

            return $user;
        }

        return null;
    }

    public function logout(Request $request): bool
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

        return true;
    }

    public function register(Request $request): array
    {
        // Collect user data from the request
        $userData = [
            'fname' => $request->request->get('fname'),
            'lname' => $request->request->get('lname'),
            'gmail' => $request->request->get('gmail'),
            'passw' => password_hash($request->request->get('passw'), PASSWORD_DEFAULT),
            'permission' => self::_DEFAULT_PERMISSION, // using default when create new user for permission
            'status' => self::_DEFAULT_STATUS, // using default when create new user for status
            'phone_num' => $request->request->get('phone_num') ?? null,
            'address' => $request->request->get('address') ?? null,
            'gender_role' => $request->request->get('gender_role')  ?? null
        ];


        $user = new UserEntity();
        $user->setFname($userData['fname']);
        $user->setLname($userData['lname']);
        $user->setGmail($userData['gmail']);
        $user->setPassw($userData['passw']);
        $user->setPermission($userData['permission']);
        $user->setStatus($userData['status']);
        $user->setPhoneNum($userData['phone_num']);
        $user->setAddress($userData['address']);
        $user->setGenderRole($userData['gender_role']);

        if ($this->userRepository->findByGmail($userData['gmail'])) {
            return [
                'status' => 'warning',
                'msg' => 'Gmail already exists. Please use a different Gmail.',
            ];
        }

        if (empty($userData['fname']) || empty($userData['lname']) || empty($userData['gmail']) || empty($userData['passw'])) {
            return [
                'status' => 'error',
                'msg' => 'All fields are required.',
            ];
        }

        $this->userRepository->save($user);

        return [
            'status' => 'success',
            'msg' => 'Register successful!',
        ];
    }
}
