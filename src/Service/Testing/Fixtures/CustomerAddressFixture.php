<?php

namespace Silecust\WebShop\Service\Testing\Fixtures;

use Silecust\WebShop\Entity\Customer;
use Silecust\WebShop\Entity\CustomerAddress;
use Silecust\WebShop\Factory\CustomerAddressFactory;
use Zenstruck\Foundry\Proxy;

trait CustomerAddressFixture
{
    private Proxy|CustomerAddress $addressBillingA;
    private Proxy|CustomerAddress $addressShippingA;

    public function createCustomerAddressA(Proxy|Customer $customer): void
    {

        $this->addressBillingA = CustomerAddressFactory::createOne(['customer' => $customer,
            'addressType' => 'billing']);
        $this->addressShippingA = CustomerAddressFactory::createOne(['customer' => $customer,
            'addressType' => 'shipping']);
    }

}