<?php

namespace Silecust\WebShop\Tests\Service\Shipping;

use Silecust\WebShop\Entity\OrderHeader;
use Silecust\WebShop\Service\Transaction\Order\Header\Shipping\ShippingOrderServiceInterface;

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