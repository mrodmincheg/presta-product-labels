<?php

namespace PrestaShop\Module\ProductLabel\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PrestaShop\Module\ProductLabel\Entity\ProductReference;
use PrestaShop\Module\ProductLabel\Repository\ProductLabelRepository;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass=ProductLabelRepository::class)
 * @ORM\Table(name="product_label")
 */
class ProductLabel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=7)
     */
    private $color;

    /**
     * @ORM\Column(type="boolean", options={"default": 0})
     */
    private $visible;

    /**
     * @ORM\ManyToMany(targetEntity=ProductReference::class)
     * @ORM\JoinTable(
     *     name="product_label_product",
     *     joinColumns={@ORM\JoinColumn(name="label_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id_product")}
     * )
     */
    private Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of color
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set the value of color
     *
     * @return  self
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get the value of visible
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * Set the value of visible
     *
     * @return  self
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(ProductReference $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
        }
        return $this;
    }

    public function removeProduct(ProductReference $product): self
    {
        $this->products->removeElement($product);
        return $this;
    }
}
