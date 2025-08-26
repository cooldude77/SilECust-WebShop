<?php

namespace Silecust\WebShop\Service\Testing\Fixtures;

use Silecust\WebShop\Entity\Customer;
use Silecust\WebShop\Entity\CustomerAddress;
use Silecust\WebShop\Factory\CustomerAddressFactory;
use Zenstruck\Foundry\Proxy;

trait CustomerAddressBFixture
{
    private Proxy|CustomerAddress $addressBillingB;
    private Proxy|CustomerAddress $addressShippingB;

    public function createCustomerAddressB(Proxy|Customer $customer): void
    {

        $this->addressBillingB = CustomerAddressFactory::createOne(['customer' => $customer,
            'addressType' => 'billing']);
        $this->addressShippingB = CustomerAddressFactory::createOne(['customer' => $customer,
            'addressType' => 'shipping']);
    }
}