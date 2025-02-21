<?php

namespace Silecust\WebShop\Event\Transaction\Order\Header;

use Silecust\WebShop\Entity\OrderHeader;

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