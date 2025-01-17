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
class ProductGroupAttributeKey
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProductGroup $productGroup = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProductAttributeKey $productAttributeKey = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductGroup(): ?ProductGroup
    {
        return $this->productGroup;
    }

    public function setProductGroup(?ProductGroup $productGroup): static
    {
        $this->productGroup = $productGroup;

        return $this;
    }

    public function getProductAttributeKey(): ?ProductAttributeKey
    {
        return $this->productAttributeKey;
    }

    public function setProductAttributeKey(?ProductAttributeKey $productAttributeKey): static
    {
        $this->productAttributeKey = $productAttributeKey;

        return $this;
    }
}
