<?php

namespace Silecust\WebShop\Tests\Controller\Admin\Customer\Order;

use Silecust\WebShop\Service\Testing\Fixtures\CartFixture;
use Silecust\WebShop\Service\Testing\Fixtures\CurrencyFixture;
use Silecust\WebShop\Service\Testing\Fixtures\CustomerFixture;
use Silecust\WebShop\Service\Testing\Fixtures\CustomerFixtureB;
use Silecust\WebShop\Service\Testing\Fixtures\LocationFixture;
use Silecust\WebShop\Service\Testing\Fixtures\OrderFixture;
use Silecust\WebShop\Service\Testing\Fixtures\OrderFixtureB;
use Silecust\WebShop\Service\Testing\Fixtures\OrderItemFixture;
use Silecust\WebShop\Service\Testing\Fixtures\OrderShippingFixture;
use Silecust\WebShop\Service\Testing\Fixtures\PriceFixture;
use Silecust\WebShop\Service\Testing\Fixtures\ProductFixture;
use Silecust\WebShop\Service\Testing\Fixtures\SessionFactoryFixture;
use Silecust\WebShop\Service\Testing\Utility\FindByCriteria;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;

class OrderHeaderControllerTest extends WebTestCase
{
    use HasBrowser,
        CurrencyFixture,
        CustomerFixture,
        CustomerFixtureB,
        ProductFixture,
        PriceFixture,
        LocationFixture,
        FindByCriteria,
        CartFixture,
        OrderFixture,
        OrderItemFixture,
        OrderFixtureB,
        SessionFactoryFixture,
        OrderShippingFixture,
        Factories;

    /**
     * @return void
     */
    public function testListOfOrdersForCustomerAShouldNotListCustomerB()
    {
        $this->createCustomerFixtures();
        $this->createCustomerFixturesB();
        $this->createProductFixtures();
        $this->createLocationFixtures();
        $this->createCurrencyFixtures($this->country);
        $this->createPriceFixtures($this->product1, $this->product2, $this->currency);
        $this->createOrderFixturesA($this->customerA);
        $this->createOrderFixturesB($this->customerB);

        $uri = "/my/orders";

        $this->browser()
            ->use(function (KernelBrowser $kernelBrowser) {
                $kernelBrowser->loginUser($this->userForCustomerA->object());
            })
            ->visit($uri)
            ->assertSee($this->inProcessOrderHeaderA->getGeneratedId())
            ->assertNotSee($this->inProcessOrderHeaderB->getGeneratedId())
            ->visit('/logout')
             ->use(function (KernelBrowser $kernelBrowser) {
                $kernelBrowser->loginUser($this->userForCustomerB->object());
            })
            ->visit($uri)
            ->assertNotSee($this->inProcessOrderHeaderA->getGeneratedId())
            ->assertSee($this->inProcessOrderHeaderB->getGeneratedId());


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
