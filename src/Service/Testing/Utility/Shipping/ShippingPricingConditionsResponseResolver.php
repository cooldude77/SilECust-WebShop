<?php

namespace Silecust\WebShop\Service\Testing\Utility\Shipping;

use Silecust\WebShop\Entity\OrderHeader;
use Silecust\WebShop\Entity\OrderShipping;
use Silecust\WebShop\Exception\Module\WebShop\External\Shipping\ShippingConditionsJsonCannotBeDecoded;
use Silecust\WebShop\Service\Transaction\Order\Header\Shipping\ShippingPricingConditionsResponseResolverInterface;

class ShippingPricingConditionsResponseResolver implements ShippingPricingConditionsResponseResolverInterface
{

    public function getShippingChargesConditionsFromAPI(OrderHeader $orderHeader): mixed
    {
        // called API, received this json
        $shippingConditionsFromAPI = '{
    "condition1": {
        "name": "Condition 1",
        "value": 50.5,
        "txnId": "txnId"
    },
    "condition2": {
        "name": "Condition 2",
        "value": 50,
        "txnId": "txnId"
        }
    }';

        $shippingConditions = json_decode($shippingConditionsFromAPI, true);
        if (!$shippingConditions)
            throw new ShippingConditionsJsonCannotBeDecoded();

        // do some calculations
        $shippingConditions += [OrderShipping::TOTAL_SHIPPING_VALUE => 100.5];

        return json_encode($shippingConditions);

    }


}