<?php

namespace App\Service\Transaction\Order\Header\Shipping;

use App\Entity\OrderHeader;

interface ShippingOrderServiceInterface
{

    public function getValueAndDataArray(OrderHeader $orderHeader): array;
}