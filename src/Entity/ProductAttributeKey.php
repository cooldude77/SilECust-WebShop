<?php

namespace App\Entity;

use App\Repository\ProductAttributeKeyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Product Attributes are independent
 * and can be connected to product
 */
#[ORM\Entity(repositoryClass: ProductAttributeKeyRepository::class)]
class ProductAttributeKey
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 1,
        max: 255,
        maxMessage: 'Length cannot exceed 255'
    )] private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 1,
        max: 255,
        maxMessage: 'Length cannot exceed 255'
    )]
    private ?string $description = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProductAttributeKeyType   $productAttributeKeyType = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getProductAttributeKeyType(): ?ProductAttributeKeyType
    {
        return $this->productAttributeKeyType;
    }

    public function setProductAttributeKeyType(ProductAttributeKeyType $productAttributeKeyType): static
    {
        $this->productAttributeKeyType = $productAttributeKeyType;

        return $this;
    }


}
