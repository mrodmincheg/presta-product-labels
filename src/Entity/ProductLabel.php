<?php

namespace PrestaShop\Module\ProductLabel\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="product_label")
 */
class ProductLabel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $name;

    /**
     * @ORM\Column(type="string", length=7)
     */
    public $color;

    /**
     * @ORM\Column(type="boolean")
     */
    public $visible;
}
