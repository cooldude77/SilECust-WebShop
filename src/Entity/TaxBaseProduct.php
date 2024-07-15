<?php

namespace App\Entity;

use App\Repository\TaxBaseProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaxBaseProductRepository::class)]
class TaxBaseProduct
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\Column]
    private ?float $taxRate = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Currency $currency = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getTaxRate(): ?float
    {
        return $this->taxRate;
    }

    public function setTaxRate(float $taxRate): static
    {
        $this->taxRate = $taxRate;

        return $this;
    }

    public function setCurrency(?Currency $currency): TaxBaseProduct
    {
        $this->currency = $currency;
        return $this;
    }

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

}
