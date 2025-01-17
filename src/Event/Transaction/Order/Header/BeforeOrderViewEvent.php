<?php

namespace App\Event\Transaction\Order\Header;

use App\Entity\OrderHeader;
use Symfony\Contracts\EventDispatcher\Event;

class BeforeOrderViewEvent extends Event
{
    const string BEFORE_ORDER_VIEW_EVENT = 'before.order.view';

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