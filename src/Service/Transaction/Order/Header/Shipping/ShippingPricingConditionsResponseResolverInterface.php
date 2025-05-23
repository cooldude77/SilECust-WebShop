<?php

namespace Silecust\WebShop\Service\Transaction\Order\Header\Shipping;

use Silecust\WebShop\Entity\OrderHeader;

interface ShippingPricingConditionsResponseResolverInterface
{

    public function getShippingChargesConditionsFromAPI(OrderHeader $orderHeader): array;

}