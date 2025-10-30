<?php

namespace App\Module\User\Service;

use App\Module\User\Entity\UserEntity;
use App\Module\User\Repository\UserRepository;


class UserService
{

    public function __construct(private UserRepository $userRepository) {}

    public function getUserByGmail(string $gmail): ?UserEntity
    {
        return $this->userRepository->findByGmail($gmail);
    }

    public function getAll(): ?array
    {
        return $this->userRepository->getAll();
    }
}
