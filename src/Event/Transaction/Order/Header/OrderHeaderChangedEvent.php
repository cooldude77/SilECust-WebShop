<?php

namespace App\Event\Transaction\Order\Header;

use App\Entity\OrderHeader;

class OrderHeaderChangedEvent
{

    const ORDER_HEADER_CHANGED = 'after.order.header.changed';

    public function __construct(private readonly OrderHeader $orderHeader)
    {
    }

    public function getOrderHeader(): OrderHeader
    {
        return $this->orderHeader;
    }
}