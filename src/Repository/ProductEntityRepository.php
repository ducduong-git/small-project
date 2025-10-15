<?php

namespace App\Repository;

use App\Entity\CategoryEntity;
use App\Entity\ProductEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductEntity>
 */
class ProductEntityRepository extends ServiceEntityRepository
{
    private const _TABLE_NAME = "product";

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
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function update(ProductEntity $productEntity): ?ProductEntity
    {
        // Entity is already managed, just flush
        $this->getEntityManager()->flush();

        return $productEntity;
    }

    public function findProduct(array $filter, array $orderBy = [], int $limit = 0, int $offset = 0): ?array
    {
        $qb = $this->createQueryBuilder('p');

        // Apply filters
        if (!empty($filter['search_name'])) {
            $qb->andWhere('p.name LIKE :name')
                ->setParameter('name', '%' . $filter['search_name'] . '%');
        }

        if (!empty($filter['filter'])) {
            $qb->andWhere('p.cateId = :cate_id')
                ->setParameter('cate_id', $filter['filter']);
        }

        // Default ordering
        $qb->orderBy('p.id', 'DESC');

        // Additional ordering
        if (!empty($orderBy)) {
            foreach ($orderBy as $field => $direction) {
                $qb->orderBy('p.' . $field, $direction);
            }
        }

        // Apply limit and offset
        if ($limit > 0) {
            $qb->setMaxResults($limit);
        }

        if ($offset > 0) {
            $qb->setFirstResult($offset);
        }

        return $qb->getQuery()->getResult();
    }

    public function getProductWithCategory(array $filter = [], string $select = '*'): ?array
    {
        $sql = 'SELECT ' . $select . ' FROM ' . self::_TABLE_NAME . ' AS prod';

        $conditions = [];
        $params = [];

        $sql .= ' LEFT JOIN category AS cate ON cate.id = prod.cate_id ';

        $sql .= ' ORDER BY prod.id';

        return $this->getEntityManager()->getConnection()->executeQuery($sql, $params)->fetchAllAssociative();
    }

    public function getProductWithCategoryDTO(array $filter = [], string $select = '*', int $limit = 0, int $offset = 0): ?array
    {
        $qb = $this->createQueryBuilder('p');

        $qb->leftJoin(CategoryEntity::class, 'cate', Join::WITH, 'p.cateId = cate.id');

        $qb->select('p, cate.name as category');

        // Apply filters
        if (!empty($filter['id'])) {
            $qb->andWhere('p.id = :id')
                ->setParameter('id', $filter['id']);
        }

        if (!empty($filter['filter'])) {
            $qb->andWhere('p.cateId = :cate_id')
                ->setParameter('cate_id', $filter['filter']);
        }

        // Default ordering
        $qb->orderBy('p.id', 'DESC');

        // Additional ordering
        if (!empty($orderBy)) {
            foreach ($orderBy as $field => $direction) {
                $qb->orderBy('p.' . $field, $direction);
            }
        }

        // Apply limit and offset
        if ($limit > 0) {
            $qb->setMaxResults($limit);
        }

        if ($offset > 0) {
            $qb->setFirstResult($offset);
        }

        return $qb->getQuery()->getResult();
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
