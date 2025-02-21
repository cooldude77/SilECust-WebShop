<?php

namespace Silecust\WebShop\Service\Transaction\Order\Header\Shipping;

use Silecust\WebShop\Entity\OrderHeader;

interface ShippingOrderServiceInterface
{

    public function getValueAndDataArray(OrderHeader $orderHeader): array;
}