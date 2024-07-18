<?php

namespace App\Entity;

use App\Repository\OrderItemPriceBreakupRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderItemPriceBreakupRepository::class)]
class OrderItemPriceBreakup
{
    public const int BASE_PRICE = 1;
    public const int DISCOUNT = 2;
    public const int RATE_OF_TAX = 3;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?OrderItem $orderItem = null;

    #[ORM\Column]
    private ?float $basePrice = null;

    #[ORM\Column(nullable: true)]
    private ?float $discount = null;

    #[ORM\Column]
    private ?float $rateOfTax = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderItem(): ?OrderItem
    {
        return $this->orderItem;
    }

    public function setOrderItem(OrderItem $orderItem): static
    {
        $this->orderItem = $orderItem;

        return $this;
    }

    public function getBasePrice(): ?float
    {
        return $this->basePrice;
    }

    public function setBasePrice(float $basePrice): static
    {
        $this->basePrice = $basePrice;

        return $this;
    }

    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    public function setDiscount(?float $discount): static
    {
        $this->discount = $discount;

        return $this;
    }

    public function getRateOfTax(): ?float
    {
        return $this->rateOfTax;
    }

    public function setRateOfTax(float $rateOfTax): static
    {
        $this->rateOfTax = $rateOfTax;

        return $this;
    }
}
