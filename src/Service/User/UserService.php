<?php

namespace App\Service\User;

use App\Entity\UserEntity;
use App\Repository\UserRepository;

class UserService
{

    public function __construct(private UserRepository $userRepository)
    {
    }

    public function getUserByGmail(string $gmail): ?UserEntity
    {
        return $this->userRepository->findByGmail($gmail);
    }

    public function getAll(): ?array
    {
        return $this->userRepository->getAll();
    }
}
