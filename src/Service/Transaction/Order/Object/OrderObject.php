<?php

namespace App\Service\Transaction\Order\Object;

use App\Entity\OrderHeader;
use App\Entity\OrderPayment;

class OrderObject
{

    private OrderHeader $orderHeader;

    private array $orderItemObjects;

    private array $orderAddress;

    private  ?OrderPayment $orderPayment = null;



    public function getOrderHeader(): OrderHeader
    {
        return $this->orderHeader;
    }

    public function setOrderHeader(OrderHeader $orderHeader): void
    {
        $this->orderHeader = $orderHeader;
    }

    public function getOrderItemObjects(): array
    {
        return $this->orderItemObjects;
    }

    public function setOrderItemObjects(array $orderItemObjects): void
    {
        $this->orderItemObjects = $orderItemObjects;
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



}