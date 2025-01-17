<?php

namespace App\Entity;

use App\Repository\OrderJournalRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OrderJournalRepository::class)]
#[HasLifecycleCallbacks]
class OrderJournal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private array $orderSnapShot = [];

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?OrderHeader $orderHeader = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderSnapShot(): array
    {
        return $this->orderSnapShot;
    }

    public function setOrderSnapShot(array $orderSnapShot): static
    {
        $this->orderSnapShot = $orderSnapShot;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
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
        $this->createdAt = new \DateTimeImmutable();
    }
}
