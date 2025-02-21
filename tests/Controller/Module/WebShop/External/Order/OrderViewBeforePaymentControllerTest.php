<?php

namespace Silecust\WebShop\Tests\Controller\Module\WebShop\External\Order;

use Silecust\WebShop\Tests\Fixtures\CartFixture;
use Silecust\WebShop\Tests\Fixtures\CurrencyFixture;
use Silecust\WebShop\Tests\Fixtures\CustomerFixture;
use Silecust\WebShop\Tests\Fixtures\LocationFixture;
use Silecust\WebShop\Tests\Fixtures\OrderFixture;
use Silecust\WebShop\Tests\Fixtures\OrderItemFixture;
use Silecust\WebShop\Tests\Fixtures\PriceFixture;
use Silecust\WebShop\Tests\Fixtures\ProductFixture;
use Silecust\WebShop\Tests\Fixtures\SessionFactoryFixture;
use Silecust\WebShop\Tests\Utility\FindByCriteria;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;

class OrderViewBeforePaymentControllerTest extends WebTestCase
{
    use HasBrowser,
        CurrencyFixture,
        CustomerFixture,
        ProductFixture,
        PriceFixture,
        LocationFixture,
        FindByCriteria,
        CartFixture,
        OrderFixture,
        OrderItemFixture,
        SessionFactoryFixture,
        Factories;
    protected function setUp(): void
    {
        $this->browser()->visit('/logout');


    }


    public function testOrderViewBeforePayment()
    {
        $this->createCustomerFixtures();
        $this->createProductFixtures();
        $this->createLocationFixtures();
        $this->createCurrencyFixtures($this->country);
        $this->createPriceFixtures($this->productA, $this->productB, $this->currency);
        $this->createOpenOrderFixtures($this->customer);
        $this->createOrderItemsFixture($this->openOrderHeader, $this->productA, $this->productB);

        $uri = '/checkout/order/view';

        $this->browser()
            ->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForCustomer->object());
            })
            ->visit($uri)
            ->assertSee(4930.5);;

    }
}
