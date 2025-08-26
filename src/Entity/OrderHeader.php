<?php

namespace Silecust\WebShop\Entity;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Silecust\WebShop\Repository\OrderHeaderRepository;

#[ORM\Entity(repositoryClass: OrderHeaderRepository::class)]
class OrderHeader
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $customer = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?DateTimeInterface $dateTimeOfOrder = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?OrderStatusType $orderStatusType = null;

    #[ORM\Column(length: 255)]
    private ?string $generatedId = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private mixed $customerInJson = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(Customer $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function getDateTimeOfOrder(): ?DateTimeInterface
    {
        return $this->dateTimeOfOrder;
    }

    public function setDateTimeOfOrder(DateTimeInterface $dateTimeOfOrder): static
    {
        $this->dateTimeOfOrder = $dateTimeOfOrder;

        return $this;
    }

    public function getOrderStatusType(): ?OrderStatusType
    {
        return $this->orderStatusType;
    }

    public function setOrderStatusType(?OrderStatusType $orderStatusType): static
    {
        $this->orderStatusType = $orderStatusType;

        return $this;
    }

    public function getGeneratedId(): ?string
    {
        return $this->generatedId;
    }

    public function setGeneratedId(string $generatedId): static
    {
        $this->generatedId = $generatedId;

        return $this;
    }

    public function getCustomerInJson(): mixed
    {
        return $this->customerInJson;
    }

    public function setCustomerInJson(mixed $customerInJson): static
    {
        $this->customerInJson = $customerInJson;

        return $this;
    }
}
