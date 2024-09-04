<?php

namespace App\Event\Transaction\Order\Item;

use App\Entity\OrderItem;

class OrderItemEditEvent
{
    const ORDER_ITEM_EDITED = 'after.order.item.change';

    /**
     * @param OrderItem $orderItem
     */
    public function __construct(private readonly OrderItem $orderItem)
    {
    }

    public function getOrderItem(): OrderItem
    {
        return $this->orderItem;
    }
}