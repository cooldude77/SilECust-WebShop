<?php

namespace Silecust\WebShop\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Silecust\WebShop\Repository\OrderJournalRepository;

#[ORM\Entity(repositoryClass: OrderJournalRepository::class)]
#[HasLifecycleCallbacks]
class OrderJournal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::JSON, nullable: false)]
    private mixed $orderSnapShot;


    #[ORM\Column(type: Types::TEXT, nullable: false)]
    private string $changeNote;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false)]
    private ?DateTimeInterface $createdAt = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?OrderHeader $orderHeader = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderSnapShot(): mixed
    {
        return $this->orderSnapShot;
    }

    public function setOrderSnapShot(mixed $orderSnapShot): void
    {
        $this->orderSnapShot = $orderSnapShot;
    }


    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
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

    #[ORM\PrePersist]
    public function uponCreationFillDefaultFields(): void
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getChangeNote(): string
    {
        return $this->changeNote;
    }

    public function setChangeNote(string $changeNote): void
    {
        $this->changeNote = $changeNote;
    }

}
