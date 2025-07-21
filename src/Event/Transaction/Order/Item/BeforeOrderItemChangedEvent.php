<?php

namespace Silecust\WebShop\Event\Transaction\Order\Item;

use Silecust\WebShop\Entity\OrderItem;

class BeforeOrderItemChangedEvent
{
    const string EVENT_NAME = 'after.order.item.change';

    /**
     * @param OrderItem $orderItem
     * @param array $requestData
     */
    public function __construct(private readonly OrderItem $orderItem, private array $requestData)
    {
    }

    public function getOrderItem(): OrderItem
    {
        return $this->orderItem;
    }

    public function getRequestData(): array
    {
        return $this->requestData;
    }


}