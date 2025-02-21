<?php

namespace Silecust\WebShop\Tests\Fixtures;

use Silecust\WebShop\Entity\Customer;
use Silecust\WebShop\Entity\CustomerAddress;
use Silecust\WebShop\Factory\CustomerAddressFactory;
use Zenstruck\Foundry\Proxy;

trait CustomerAddressFixture
{
    private Proxy|CustomerAddress $addressBilling;
    private Proxy|CustomerAddress $addressShipping;

    public function createCustomerAddress(Proxy|Customer $customer): void
    {

        $this->addressBilling = CustomerAddressFactory::createOne(['customer' => $customer,
                                                                   'addressType' => 'billing']);
        $this->addressShipping = CustomerAddressFactory::createOne(['customer' => $customer,
                                                                    'addressType' => 'shipping']);
    }
}