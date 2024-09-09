<?php

namespace App\Entity;

use App\Repository\ProductGroupAttributeKeyValueVisiblityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductGroupAttributeKeyValueVisiblityRepository::class)]
class ProductGroupAttributeKeyValueVisiblity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProductGroup $productGroup = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProductAttributeKey $productAttributeKey = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProductAttributeKeyValue $productAttributeKeyValue = null;


    #[ORM\Column]
    private ?bool $isAvailable = null;

     public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
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

    public function isAvailable(): ?bool
    {
        return $this->isAvailable;
    }

    public function setAvailable(bool $isAvailable): static
    {
        $this->isAvailable = $isAvailable;

        return $this;
    }

    public function getProductAttributeKeyValue(): ?ProductAttributeKeyValue
    {
        return $this->productAttributeKeyValue;
    }

    public function setProductAttributeKeyValue(?ProductAttributeKeyValue $productAttributeKeyValue): static
    {
        $this->productAttributeKeyValue = $productAttributeKeyValue;

        return $this;
    }
}
