<?php

namespace App\Entity;

use App\Repository\OrderPaymentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderPaymentRepository::class)]
class OrderPayment
{
    const string PAYMENT_GATEWAY_RESPONSE = 'PAYMENT_GATEWAY_RESPONSE';
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?OrderHeader $orderHeader = null;

    #[ORM\Column]
    private array $paymentResponse = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderHeader(): ?OrderHeader
    {
        return $this->orderHeader;
    }

    public function setOrderHeader(OrderHeader $orderHeader): static
    {
        $this->orderHeader = $orderHeader;

        return $this;
    }

    public function setPaymentResponse(array $paymentResponse): static
    {
        $this->paymentResponse = $paymentResponse;

        return $this;
    }
}
