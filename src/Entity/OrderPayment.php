<?php

namespace Silecust\WebShop\Entity;

use Doctrine\ORM\Mapping as ORM;
use Silecust\WebShop\Repository\OrderPaymentRepository;
use Symfony\Component\Validator\Constraints as Assert;

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

    #[ORM\Column(length: 5000)]
    #[Assert\Length(
        min: 1,
        max: 5000,
        maxMessage: 'Length cannot exceed 255'
    )]
    private ?string $paymentResponse;

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

    public function setPaymentResponse(string $paymentResponse): static
    {
        $this->paymentResponse = $paymentResponse;

        return $this;
    }

    public function getPaymentResponse(): ?string
    {
        return $this->paymentResponse;
    }

}
