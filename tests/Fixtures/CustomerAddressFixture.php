<?php

namespace App\Tests\Fixtures;

use App\Entity\Customer;
use App\Entity\CustomerAddress;
use App\Factory\CustomerAddressFactory;
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