<?php

namespace Silecust\WebShop\Event\Transaction\Order\Item;

use Silecust\WebShop\Entity\OrderItem;

class OrderItemEditEvent
{
    const string EVENT_NAME = 'after.order.item.change';

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