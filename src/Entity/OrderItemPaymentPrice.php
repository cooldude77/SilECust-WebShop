<?php

namespace Silecust\WebShop\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Silecust\WebShop\Repository\OrderItemPaymentPriceRepository;

#[ORM\Entity(repositoryClass: OrderItemPaymentPriceRepository::class)]
class OrderItemPaymentPrice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $basePrice = null;

    #[ORM\Column(nullable: true)]
    private ?float $discount = null;

    #[ORM\Column]
    private ?float $taxRate = null;

    #[ORM\OneToOne(targetEntity:OrderItem::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false,onDelete :'CASCADE')]
    private ?OrderItem $orderItem = null;

    #[ORM\Column(type: Types::JSON)]
    private mixed $basePriceInJson = null;

    #[ORM\Column(type: Types::JSON)]
    private mixed $discountsInJson = null;

    #[ORM\Column(type: Types::JSON)]
    private mixed $taxationInJson = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTaxRate(): ?float
    {
        return $this->taxRate;
    }

    public function setTaxRate(float $taxRate): static
    {
        $this->taxRate = $taxRate;

        return $this;
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

    public function getBasePriceInJson(): mixed
    {
        return $this->basePriceInJson;
    }

    public function setBasePriceInJson(mixed $basePriceInJson): void
    {
        $this->basePriceInJson = $basePriceInJson;
    }

    public function getDiscountsInJson(): mixed
    {
        return $this->discountsInJson;
    }

    public function setDiscountsInJson(mixed $discountsInJson): void
    {
        $this->discountsInJson = $discountsInJson;
    }

    public function getTaxationInJson(): mixed
    {
        return $this->taxationInJson;
    }

    public function setTaxationInJson(mixed $taxationInJson): void
    {
        $this->taxationInJson = $taxationInJson;
    }

}
