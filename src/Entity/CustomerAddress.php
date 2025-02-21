<?php

namespace Silecust\WebShop\Entity;

use Silecust\WebShop\Repository\CustomerAddressRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CustomerAddressRepository::class)]
class CustomerAddress
{

    public const ADDRESS_TYPE_SHIPPING = 'shipping';
    public const ADDRESS_TYPE_BILLING = 'billing';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $customer = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 1,
        max: 255,
        maxMessage: 'Length cannot exceed 255'
    )]
    private ?string $line1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $line2 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $line3 = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?PostalCode $postalCode = null;

    #[ORM\Column(length: 255)]
    private ?string $addressType = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isDefault = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function getLine1(): ?string
    {
        return $this->line1;
    }

    public function setLine1(?string $line1): void
    {
        $this->line1 = $line1;
    }

    public function getLine2(): ?string
    {
        return $this->line2;
    }

    public function setLine2(?string $line2): void
    {
        $this->line2 = $line2;
    }

    public function getLine3(): ?string
    {
        return $this->line3;
    }

    public function setLine3(?string $line3): void
    {
        $this->line3 = $line3;
    }

    public function getPostalCode(): ?PostalCode
    {
        return $this->postalCode;
    }

    public function setPostalCode(?PostalCode $postalCode): void
    {
        $this->postalCode = $postalCode;
    }

    public function getAddressType(): ?string
    {
        return $this->addressType;
    }

    public function setAddressType(string $addressType): static
    {
        $this->addressType = $addressType;

        return $this;
    }

    public function isDefault(): ?bool
    {
        return $this->isDefault;
    }

    public function setDefault(?bool $isDefault): static
    {
        $this->isDefault = $isDefault;

        return $this;
    }
}
