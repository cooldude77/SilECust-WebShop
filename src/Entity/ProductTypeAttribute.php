<?php

namespace App\Entity;

use App\Repository\ProductTypeAttributeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Relationship between ProductAttributes
 * And Product Type
 * One Product Type can have multiple attributes
 * And vice versa
 */
#[ORM\Entity(repositoryClass: ProductTypeAttributeRepository::class)]
class ProductTypeAttribute
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProductType $productType = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProductAttribute $productAttribute = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductType(): ?ProductType
    {
        return $this->productType;
    }

    public function setProductType(?ProductType $productType): static
    {
        $this->productType = $productType;

        return $this;
    }

    public function getProductAttribute(): ?ProductAttribute
    {
        return $this->productAttribute;
    }

    public function setProductAttribute(?ProductAttribute $productAttribute): static
    {
        $this->productAttribute = $productAttribute;

        return $this;
    }
}
