<?php

namespace PrestaShop\Module\ProductLabel\Service;

use Doctrine\ORM\EntityManagerInterface;

class LabelsFrontProvider
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getLabelsForProduct(int $productId)
    {
        $dql = '
            SELECT l
            FROM PrestaShop\Module\ProductLabel\Entity\ProductLabel l
            JOIN l.products p
            WHERE p.id = :productId
            AND l.visible = true
        ';

        $query = $this->entityManager->createQuery($dql);
        $query->setParameter('productId', $productId);

        return $query->getResult();
    }
}