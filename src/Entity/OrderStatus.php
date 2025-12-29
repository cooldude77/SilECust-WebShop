<?php

namespace Silecust\WebShop\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Silecust\WebShop\Repository\OrderStatusRepository;

#[ORM\Entity(repositoryClass: OrderStatusRepository::class)]
#[HasLifecycleCallbacks]
class OrderStatus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?OrderHeader $orderHeader = null;

    #[ORM\ManyToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?OrderStatusType $orderStatusType = null;

    /**
     * @var \DateTimeInterface|null
     * See Order.md for conversion to UTC
     * This date cannot be changed once set
     */
    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private ?DateTimeInterface $statusCreatedAt = null;


    #[ORM\Column(length: 255)]
    private string|null $statusCreatedAtTimeZone = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $note = null;
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

    public function getStatusCreatedAt(): ?DateTimeInterface
    {
        return $this->statusCreatedAt;
    }

    public function setStatusCreatedAt(DateTimeInterface $statusCreatedAt): static
    {
        $this->statusCreatedAt = $statusCreatedAt;

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


    #[ORM\PrePersist]
    public function setDatesOnCreation(): void
    {
        $presentDateAndTime = new DateTimeImmutable('now');

        $this->setStatusCreatedAt($presentDateAndTime);
        $this->statusCreatedAtTimeZone = $presentDateAndTime->getTimezone()->getName();

    }

    public function getStatusCreatedAtTimeZone(): ?string
    {
        return $this->statusCreatedAtTimeZone;
    }


}
