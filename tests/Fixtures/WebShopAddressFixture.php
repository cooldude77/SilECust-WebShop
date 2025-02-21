<?php

namespace Silecust\WebShop\Tests\Fixtures;

use Silecust\WebShop\Entity\CustomerAddress;
use Silecust\WebShop\Service\Module\WebShop\External\Address\CheckOutAddressSession;
use Silecust\WebShop\Tests\Utility\MySessionFactory;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Zenstruck\Foundry\Proxy;

trait WebShopAddressFixture
{
    private function setAddressesInSession(KernelBrowser $browser,
        Proxy|CustomerAddress $addressShipping, Proxy|CustomerAddress $addressBilling
    ): void {
        /** @var MySessionFactory $factory */
        $factory = $browser->getContainer()->get('session.factory');

        $session = $factory->createSession();
        $session->set(CheckOutAddressSession::SHIPPING_ADDRESS_ID, $addressShipping->getId());
        $session->set(CheckOutAddressSession::BILLING_ADDRESS_ID, $addressBilling->getId());
        $session->save();
    }


}