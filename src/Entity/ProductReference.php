<?php

namespace PrestaShop\Module\ProductLabel\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="product")
 */
class ProductReference
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id_product", type="integer")
     */
    public int $id;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    public ?string $reference = null;

    public function getReference(): string
    {
        return $this->reference ?? '[No Reference]';
    }
}