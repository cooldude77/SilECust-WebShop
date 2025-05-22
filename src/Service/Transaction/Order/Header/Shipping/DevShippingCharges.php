<?php

namespace Silecust\WebShop\Service\Transaction\Order\Header\Shipping;

use Silecust\WebShop\Entity\OrderHeader;
use Silecust\WebShop\Service\Security\User\Customer\CustomerFromUserFinder;
use Silecust\WebShop\Service\Transaction\Order\OrderRead;

/**
 * Sample class for Shipping charge, normally API would return these values
 */
class DevShippingCharges implements ShippingOrderServiceInterface
{
    public function __construct(private readonly CustomerFromUserFinder $customerFromUserFinder,
                                private readonly OrderRead              $orderRead)
    {
    }

    public function getShippingChargesConditionsFromAPI(OrderHeader $orderHeader): array
    {
        // This should be the format
        return [

            'condition1' => [
                'name' => 'condition_1',
                'value' => 100.5,
                'currency' => 'INR',
                'data' => ['Description' => 'shipping charges']],

            'condition2' => [
                'name' => 'condition_2',
                'value' => 10,
                'currency' => 'INR',
                'data' => ['Description' => 'Customs Fee']]

        ];

    }

    public function shippingDataExists(array $shippingData)
    {
        $orderHeader = $this->orderRead->getOpenOrder($this->customerFromUserFinder->getLoggedInCustomer());

        $this->orderRead->getShippingData($orderHeader);
        if ($this->orderRead->getShippingData($orderHeader) != null)
            foreach ($this->orderRead->getShippingData($orderHeader) as $key => $value) {


            }

        return false;
    }
}