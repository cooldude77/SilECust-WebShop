<?php

namespace App\Service\Transaction\Order\Object;

use App\Entity\OrderItem;
use App\Entity\OrderItemPriceBreakup;

class OrderItemObject
{

    private OrderItem $orderItem;

    private OrderItemPriceBreakup $orderItemPriceBreakUp;

    public function getOrderItem(): OrderItem
    {
        return $this->orderItem;
    }

    public function setOrderItem(OrderItem $orderItem): void
    {
        $this->orderItem = $orderItem;
    }

    public function getOrderItemPriceBreakUp(): OrderItemPriceBreakup
    {
        return $this->orderItemPriceBreakUp;
    }



    public function setOrderItemPriceBreakUp(OrderItemPriceBreakup $orderItemPriceBreakUp):void
    {
        $this->orderItemPriceBreakUp = $orderItemPriceBreakUp;
    }

}