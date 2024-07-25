<?php

namespace App\Service\Transaction\Order\Object;

use App\Entity\OrderHeader;
use App\Entity\OrderPayment;

class OrderObject
{

    private OrderHeader $orderHeader;

    private array $orderItems;

    private array $orderAddress;

    private  ?OrderPayment $orderPayment = null;

private  ?array $orderItemPaymentPrices = null;

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

    public function setOrderPayment(?OrderPayment $orderPayment =null): void
    {
        $this->orderPayment = $orderPayment;
    }

    public function setOrderItemPaymentPrices(array $orderItemPaymentPrices): void
    {
        $this->orderItemPaymentPrices = $orderItemPaymentPrices;
    }


}