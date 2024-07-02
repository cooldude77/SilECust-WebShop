<?php

namespace App\Service\Module\WebShop\External\Order\Object;

use App\Entity\OrderHeader;
use App\Entity\OrderPayment;

class OrderObject
{

    private OrderHeader $orderHeader;

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

    public function setOrderItems(array $orderItems): void
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
    private array $orderItems;

    private array $orderAddress;

    private  OrderPayment $orderPayment;

    public function getOrderPayment(): OrderPayment
    {
        return $this->orderPayment;
    }

    public function setOrderPayment(OrderPayment $orderPayment): void
    {
        $this->orderPayment = $orderPayment;
    }


}