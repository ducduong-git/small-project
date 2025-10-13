<?php

namespace App\Repository;

use App\Entity\ProductEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductEntity>
 */
class ProductEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductEntity::class);
    }

    public function getAllProduct(): ?array
    {
        return $this->findAll();
    }

    public function findOneProduct(int $id): ?ProductEntity
    {
        return $this->find($id);
    }

    public function addNewProduct(ProductEntity $productEntity): ?ProductEntity
    {
        $this->getEntityManager()->persist($productEntity);
        $this->getEntityManager()->flush();
        return $productEntity;
    }

    public function getExistProduct(): ?array
    {
        return $this->createQueryBuilder('p')
            ->where('p.deletedAt IS NULL')
            ->where('p.status != 0')
            ->getQuery()
            ->getResult();
    }

    public function update(ProductEntity $productEntity): ?ProductEntity
    {
         // Entity is already managed, just flush
        $this->getEntityManager()->flush();

        return $productEntity;
    }

    // public function softDeleteCategory(int $id): ProductEntity
    // {
    //     $now = new \DateTimeImmutable();
    //     $category = $this->find($id);
    //     $category->setDeletedAt($now);
    //     $category->setStatus(0);

    //     $this->getEntityManager()->flush();

    //     return $category;
    // }
}
