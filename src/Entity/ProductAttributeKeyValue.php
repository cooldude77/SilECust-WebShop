<?php

namespace App\Entity;

use App\Repository\ProductAttributeKeyValueRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductAttributeKeyValueRepository::class)]
class ProductAttributeKeyValue
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
    )]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 1,
        max: 255,
        maxMessage: 'Length cannot exceed 255'
    )]
    private ?string $value = null;

    #[ORM\ManyToOne()]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProductAttributeKey $productAttributeKey = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }
}
