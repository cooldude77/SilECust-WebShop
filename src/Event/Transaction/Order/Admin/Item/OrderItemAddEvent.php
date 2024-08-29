<?php

namespace App\Event\Transaction\Order\Admin\Item;

use App\Entity\OrderItem;

class OrderItemAddEvent
{
    const ORDER_ITEM_ADDED = 'after.order.item.added';

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