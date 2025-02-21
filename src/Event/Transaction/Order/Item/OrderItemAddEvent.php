<?php

namespace Silecust\WebShop\Event\Transaction\Order\Item;

use Silecust\WebShop\Entity\OrderItem;

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