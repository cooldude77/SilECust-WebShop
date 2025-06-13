<?php

namespace Silecust\WebShop\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Silecust\WebShop\Repository\OrderAddressRepository;

#[ORM\Entity(repositoryClass: OrderAddressRepository::class)]
class OrderAddress
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne]
    #[ORM\JoinColumn(nullable: false, onDelete :'CASCADE')]
    private ?OrderHeader $orderHeader = null;

    #[ORM\ManyToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?CustomerAddress $shippingAddress = null;

    #[ORM\ManyToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?CustomerAddress $billingAddress = null;

    #[ORM\Column(type: Types::JSON)]
    private mixed $shippingAddressInJson = null;

    #[ORM\Column(type: Types::JSON)]
    private mixed $billingAddressInJson = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderHeader(): ?OrderHeader
    {
        return $this->orderHeader;
    }

    public function setOrderHeader(?OrderHeader $orderHeader): void
    {
        $this->orderHeader = $orderHeader;
    }

    public function getShippingAddress(): ?CustomerAddress
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress(CustomerAddress $shippingAddress): static
    {
        $this->shippingAddress = $shippingAddress;

        return $this;
    }

    public function getBillingAddress(): ?CustomerAddress
    {
        return $this->billingAddress;
    }

    public function setBillingAddress(CustomerAddress $billingAddress): static
    {
        $this->billingAddress = $billingAddress;

        return $this;
    }

    public function getShippingAddressInJson(): mixed
    {
        return $this->shippingAddressInJson;
    }

    public function setShippingAddressInJson(mixed $shippingAddressInJson): void
    {
        $this->shippingAddressInJson = $shippingAddressInJson;
    }

    public function getBillingAddressInJson(): mixed
    {
        return $this->billingAddressInJson;
    }

    public function setBillingAddressInJson(mixed $billingAddressInJson): void
    {
        $this->billingAddressInJson = $billingAddressInJson;
    }

}
