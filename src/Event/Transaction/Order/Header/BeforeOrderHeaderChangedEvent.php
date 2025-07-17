<?php

namespace Silecust\WebShop\Event\Transaction\Order\Header;

use Silecust\WebShop\Entity\OrderHeader;

class BeforeOrderHeaderChangedEvent
{

    const string EVENT_NAME = 'after.order.header.changed';
    private OrderHeader $orderHeader;
    private array $requestData;

    public function getOrderHeader(): OrderHeader
    {
        return $this->orderHeader;
    }

    public function getRequestData(): array
    {
        return $this->requestData;
    }

    public function setOrderHeader(OrderHeader $orderHeader): void
    {
        $this->orderHeader = $orderHeader;
    }

    public function setRequestData(array $requestData): void
    {
        $this->requestData = $requestData;
    }


}