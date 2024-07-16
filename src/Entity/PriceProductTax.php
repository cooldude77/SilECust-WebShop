<?php

namespace App\Entity;

use App\Repository\PriceProductTaxRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PriceProductTaxRepository::class)]
class PriceProductTax
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?TaxSlab $taxSlab = null;

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

    public function getTaxSlab(): ?TaxSlab
    {
        return $this->taxSlab;
    }

    public function setTaxSlab(?TaxSlab $taxSlab): static
    {
        $this->taxSlab = $taxSlab;

        return $this;
    }
}
