<?php

namespace App\Service\Transaction\Order\Header\Shipping;

use App\Entity\OrderHeader;

class DevShippingCharges implements ShippingOrderServiceInterface
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