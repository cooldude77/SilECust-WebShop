<?php

namespace App\Entity;

use App\Repository\OrderStatusRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderStatusRepository::class)]
class OrderStatus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?OrderHeader $orderHeader = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?OrderStatusType $orderStatusType = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateOfStatusSet = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $note = null;

    #[ORM\Column(type: Types::OBJECT)]
    private ?object $snapShot = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderHeader(): ?OrderHeader
    {
        return $this->orderHeader;
    }

    public function setOrderHeader(?OrderHeader $orderHeader): static
    {
        $this->orderHeader = $orderHeader;

        return $this;
    }

    public function getOrderStatusType(): ?OrderStatusType
    {
        return $this->orderStatusType;
    }

    public function setOrderStatusType(OrderStatusType $orderStatusType): static
    {
        $this->orderStatusType = $orderStatusType;

        return $this;
    }

    public function getDateOfStatusSet(): ?\DateTimeInterface
    {
        return $this->dateOfStatusSet;
    }

    public function setDateOfStatusSet(\DateTimeInterface $dateOfStatusSet): static
    {
        $this->dateOfStatusSet = $dateOfStatusSet;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(string $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getSnapShot(): ?object
    {
        return $this->snapShot;
    }

    public function setSnapShot(object $snapShot): static
    {
        $this->snapShot = $snapShot;

        return $this;
    }
}
