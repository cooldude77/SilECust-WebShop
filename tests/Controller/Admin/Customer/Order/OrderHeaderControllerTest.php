<?php

namespace Silecust\WebShop\Tests\Controller\Admin\Customer\Order;

use Silecust\WebShop\Service\Testing\Fixtures\CartFixture;
use Silecust\WebShop\Service\Testing\Fixtures\CurrencyFixture;
use Silecust\WebShop\Service\Testing\Fixtures\CustomerFixture;
use Silecust\WebShop\Service\Testing\Fixtures\LocationFixture;
use Silecust\WebShop\Service\Testing\Fixtures\OrderFixture;
use Silecust\WebShop\Service\Testing\Fixtures\OrderItemFixture;
use Silecust\WebShop\Service\Testing\Fixtures\OrderShippingFixture;
use Silecust\WebShop\Service\Testing\Fixtures\PriceFixture;
use Silecust\WebShop\Service\Testing\Fixtures\ProductFixture;
use Silecust\WebShop\Service\Testing\Fixtures\SessionFactoryFixture;
use Silecust\WebShop\Service\Testing\Utility\FindByCriteria;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;

class OrderHeaderControllerTest extends WebTestCase
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
        OrderShippingFixture,
        Factories;

    /**
     * @return void
     */
    public function testDashboardWithCustomer()
    {
        $this->createCustomerFixtures();
        $this->createProductFixtures();
        $this->createLocationFixtures();
        $this->createCurrencyFixtures($this->country);
        $this->createPriceFixtures($this->productA, $this->productB, $this->currency);
        $this->createOrderFixturesA($this->customerA);
        $this->createOpenOrderItemsFixtureA($this->openOrderHeaderA, $this->productA, $this->productB);
        $this->createOrderShippingFixture($this->openOrderHeaderA);

        // Unauthenticated entry
        $uri = '/my/orders';

        $this->browser()
            ->visit($uri)
            ->assertNotAuthenticated()
            ->use(function (KernelBrowser $kernelBrowser) {
                $kernelBrowser->loginUser($this->userForCustomerA->object());
            })
            ->visit($uri)
            ->assertSuccessful()
            ->assertSee($this->afterPaymentSuccessOrderHeaderA->getGeneratedId())
            ->assertNotSee($this->openOrderHeaderA->getGeneratedId());


    }

    protected function setUp(): void
    {

        // When tests are run together , there might be a conflict in case of login user from another test not
        // logged out before another user login is tested and errors may happen
        // Individually these tests may run fine
        // So users are logged out before testing

        parent::setUp();
        $this->browser()->visit('/logout');

    }

}
