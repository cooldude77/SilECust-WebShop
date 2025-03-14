<?php

namespace Silecust\WebShop\Service\Transaction\Order\Object;

use Silecust\WebShop\Entity\OrderHeader;
use Silecust\WebShop\Entity\OrderPayment;

class OrderObject
{

    private OrderHeader $orderHeader;

    private array $orderItems;

    private array $orderAddress;

    private ?OrderPayment $orderPayment = null;

    private ?array $orderItemPaymentPrices = null;
    private ?array $orderShipping = null;

    public function getOrderHeader(): OrderHeader
    {
        return $this->orderHeader;
    }

    public function setOrderHeader(OrderHeader $orderHeader): void
    {
        $this->orderHeader = $orderHeader;
    }

    public function getOrderItems(): array
    {
        return $this->orderItems;
    }

    public function setOrderItems(?array $orderItems = null): void
    {
        $this->orderItems = $orderItems;
    }

    public function getOrderAddress(): array
    {
        return $this->orderAddress;
    }

    public function setOrderAddress(array $orderAddress): void
    {
        $this->orderAddress = $orderAddress;
    }

    public function getOrderPayment(): OrderPayment
    {
        return $this->orderPayment;
    }

    public function setOrderPayment(?OrderPayment $orderPayment = null): void
    {
        $this->orderPayment = $orderPayment;
    }

    public function setOrderItemPaymentPrices(array $orderItemPaymentPrices): void
    {
        $this->orderItemPaymentPrices = $orderItemPaymentPrices;
    }

    public function setOrderShipping(array $orderShipping)
    {
        $this->orderShipping = $orderShipping;
    }

    public function getOrderShipping(): ?array
    {
        return $this->orderShipping;
    }


}