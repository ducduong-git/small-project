<?php

namespace App\Service\User;

use App\Entity\UserEntity;
use App\Repository\UserRepository;

class UserService
{

    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUserByGmail(string $gmail): ?UserEntity
    {
        return $this->userRepository->findByGmail($gmail);
    }

    public function createUser(array $userData): UserEntity
    {
        $user = new UserEntity();
        $user->setFname($userData['fname']);
        $user->setLname($userData['lname']);
        $user->setGmail($userData['gmail']);
        $user->setPassw(password_hash($userData['passw'], PASSWORD_DEFAULT));
        $user->setPermission($userData['permission'] ?? 0);
        $user->setStatus(1); // Active by default
        $user->setPhoneNum($userData['phone_num'] ?? null);
        $user->setAddress($userData['address'] ?? null);
        $user->setGenderRole($userData['gender_role'] ?? null);

        return $this->userRepository->save($user);
    }
}
