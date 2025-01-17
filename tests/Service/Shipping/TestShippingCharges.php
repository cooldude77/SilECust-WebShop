<?php

namespace App\Tests\Service\Shipping;

use App\Entity\OrderHeader;
use App\Service\Transaction\Order\Header\Shipping\ShippingOrderServiceInterface;

class TestShippingCharges implements ShippingOrderServiceInterface
{

    public function getValueAndDataArray(OrderHeader $orderHeader): array
    {
        return [
            'condition1' => [
                'name' => 'Condition 1',
                'value' => 100.5,
                'data' => ['txnId' => 'txnId']]

        ];
    }
}