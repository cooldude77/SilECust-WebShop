<?php

namespace Silecust\WebShop\Entity;

use Silecust\WebShop\Repository\OrderAddressRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
    #[ORM\JoinColumn]
    private ?CustomerAddress $shippingAddress = null;

    #[ORM\ManyToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn]
    private ?CustomerAddress $billingAddress = null;


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
}
