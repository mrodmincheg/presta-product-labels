<?php

namespace PrestaShop\Module\ProductLabel\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PrestaShop\Module\ProductLabel\Entity\ProductLabel;

class ProductLabelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductLabel::class);
    }

    /**
     * Get all labels assigned to a specific product.
     */
    public function findByProductId(int $productId): array
    {
        return $this->createQueryBuilder('l')
            ->join('l.products', 'p')
            ->where('p.id = :id')
            ->setParameter('id', $productId)
            ->getQuery()
            ->getResult();
    }

    public function finaAllWithProducts(): array
    {
        return $this->createQueryBuilder('l')
            ->leftJoin('l.products', 'p')
            ->addSelect('p')
            ->getQuery()
            ->getResult();
    }
}
