<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
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
    #[Assert\Regex(
        pattern: '/[A-Za-z0-9\-\_\s]/',
        message: 'Only characters and numbers are allowed',
        match: true
    )]
    private ?string $name = null;

    #[ORM\Column(length: 5000)]
    #[Assert\Length(
        min: 1,
        max: 255,
        maxMessage: 'Length cannot exceed 255'
    )]
    private ?string $description = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull()]
    private ?Category $category = null;

    #[ORM\ManyToOne]
    private ?ProductGroup $productGroup = null;

    #[ORM\Column]
    private ?bool $isActive = false;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $longDescription = null;


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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

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

    public function __toString(): string
    {
        return $this->name;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getLongDescription(): ?string
    {
        return $this->longDescription;
    }

    public function setLongDescription(?string $longDescription): static
    {
        $this->longDescription = $longDescription;

        return $this;
    }


}
