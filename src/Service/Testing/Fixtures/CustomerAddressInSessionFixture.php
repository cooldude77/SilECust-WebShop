<?php

namespace Silecust\WebShop\Service\Testing\Fixtures;

use Silecust\WebShop\Entity\CustomerAddress;
use Silecust\WebShop\Service\Module\WebShop\External\Address\CheckOutAddressSession;
use Symfony\Component\HttpFoundation\Session\Session;
use Zenstruck\Foundry\Proxy;

trait CustomerAddressInSessionFixture
{
    private Proxy|CustomerAddress $addressBilling;
    private Proxy|CustomerAddress $addressShipping;

    public function createCustomerAddressInSession(Session $session,
        Proxy|CustomerAddress $addressShipping, Proxy|CustomerAddress $addressBilling
    ):
    void {

        $session->set(CheckOutAddressSession::BILLING_ADDRESS_ID, $addressBilling->getId());
        $session->set(CheckOutAddressSession::SHIPPING_ADDRESS_ID, $addressShipping->getId());

    }
}