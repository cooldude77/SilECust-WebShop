<?php

namespace App\Entity;

use App\Repository\OrderHeaderRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderHeaderRepository::class)]
class OrderHeader
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $customer = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateTimeOfOrder = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?OrderStatusType $orderStatusType = null;

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

    public function getDateTimeOfOrder(): ?\DateTimeInterface
    {
        return $this->dateTimeOfOrder;
    }

    public function setDateTimeOfOrder(\DateTimeInterface $dateTimeOfOrder): static
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
}
