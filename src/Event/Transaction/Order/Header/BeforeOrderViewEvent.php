<?php

namespace Silecust\WebShop\Event\Transaction\Order\Header;

use Silecust\WebShop\Entity\OrderHeader;
use Symfony\Contracts\EventDispatcher\Event;

class BeforeOrderViewEvent extends Event
{
    const string EVENT_NAME = 'before.order.view';

    /**
     * @param OrderHeader $orderHeader
     */
    public function __construct(private readonly OrderHeader $orderHeader)
    {
    }

    public function getOrderHeader(): OrderHeader
    {
        return $this->orderHeader;
    }
}