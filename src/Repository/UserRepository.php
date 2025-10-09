<?php

namespace App\Repository;

use App\Entity\UserEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserEntity::class);
    }

    // Read (Find by Gmail)
    public function findByGmail(string $gmail): ?UserEntity
    {
        return $this->findOneBy(['gmail' => $gmail, 'status' => 1]);
    }

    public function save(UserEntity $user): UserEntity
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
        return $user;
    }
}
