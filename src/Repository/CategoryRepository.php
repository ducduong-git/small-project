<?php

namespace App\Repository;

use App\Entity\CategoryEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends ServiceEntityRepository<CategoryEntity>
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryEntity::class);
    }

    public function addNewCategory(CategoryEntity $category): CategoryEntity
    {
        $this->getEntityManager()->persist($category);
        $this->getEntityManager()->flush();
        return $category;
    }

    public function getAllCategory(): ?array
    {
        return $this->findAll();
    }

    public function findOneCategory(int $id): ?CategoryEntity
    {
        return $this->find($id);
    }

    public function getExistCategory(): ?array
    {
        return $this->createQueryBuilder('c')
            ->where('c.deletedAt IS NULL')
            ->where('c.status != 0')
            ->getQuery()
            ->getResult();
    }

    public function updateCategory(int $id, string $newName, ?int $status = null, ?int $parentId = null): CategoryEntity
    {
        $category = $this->find($id);
        $category->setName($newName);

        if ($status !== null) {
            $category->setStatus($status);
        }

        if ($parentId !== null) {
            $category->setParentId($parentId);
        }

        $this->getEntityManager()->flush();

        return $category;
    }

    public function softDeleteCategory(int $id): CategoryEntity
    {
        $now = new \DateTimeImmutable();
        $category = $this->find($id);
        $category->setDeletedAt($now);
        $category->setStatus(0);

        $this->getEntityManager()->flush();

        return $category;
    }
}
