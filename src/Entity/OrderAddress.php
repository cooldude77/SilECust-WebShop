<?php

namespace Silecust\WebShop\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Silecust\WebShop\Repository\OrderAddressRepository;
use Symfony\Component\Serializer\Attribute\Ignore;

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

    // fetch eager because this information is needed for serialization
    // postal code fetch eager is not needed
    #[ORM\ManyToOne(cascade: ['persist', 'remove'],fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?CustomerAddress $shippingAddress = null;

    #[ORM\ManyToOne(cascade: ['persist', 'remove'],fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?CustomerAddress $billingAddress = null;

    #[Ignore]
    #[ORM\Column(type: Types::JSON,nullable: true)]
    private mixed $shippingAddressInJson = null;

    #[Ignore]
    #[ORM\Column(type: Types::JSON,nullable: true)]
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
