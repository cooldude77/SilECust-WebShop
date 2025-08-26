<?php

namespace Silecust\WebShop\Tests\Controller\Admin\Customer\Order\Access;

use Silecust\WebShop\Service\Testing\Fixtures\CartFixture;
use Silecust\WebShop\Service\Testing\Fixtures\CurrencyFixture;
use Silecust\WebShop\Service\Testing\Fixtures\CustomerFixture;
use Silecust\WebShop\Service\Testing\Fixtures\CustomerFixtureB;
use Silecust\WebShop\Service\Testing\Fixtures\EmployeeFixture;
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
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
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
        EmployeeFixture,
        Factories;

    /**
     * @return void
     */
    public function testListOfOrdersForCustomerAShouldNotListOrdersOfCustomerB()
    {
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

    /**
     * @return void
     */
    public function testIllegalDisplayOfOrderWhenGeneratedIdMayBeGuessed()
    {
        $this->createOrderFixturesA($this->customerA);
        $this->createOpenOrderItemsFixtureA($this->inProcessOrderHeaderA, $this->product1, $this->product2);

        $this->createOrderFixturesB($this->customerB);

        $uri = "/my/orders/{$this->inProcessOrderHeaderA->getGeneratedId()}/display";

        $this->browser()
            ->use(function (KernelBrowser $kernelBrowser) {
                $kernelBrowser->loginUser($this->userForCustomerA->object());
            })
            ->visit($uri)
            ->assertSee($this->inProcessOrderHeaderA->getGeneratedId())
            ->visit('/logout')
            // login with another user
            // and visit illegal Url again
            ->use(function (KernelBrowser $kernelBrowser) {
                $kernelBrowser->loginUser($this->userForCustomerB->object());
            })
            ->visit($uri)
            ->expectException(AccessDeniedException::class);
    }

    /**
     * @return void
     */
    public function doNotDeleteTestAccessEmployeeCanDisplayAllOrders()
    {
        // Silecust\WebShop\Tests\Controller\Transaction\Order\Admin\Header\OrderHeaderController
    }

    public function testCustomerCannotEditAnOrder()
    {
        $this->createOrderFixturesA($this->customerA);
        $this->createOpenOrderItemsFixtureA($this->inProcessOrderHeaderA, $this->product1, $this->product2);

        $uri = "/admin/order/{$this->inProcessOrderHeaderA->getGeneratedId()}/edit";


        $this
            ->browser()
            ->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForCustomerA->object());
            })
            ->visit($uri)
            ->expectException(AccessDeniedException::class);

    }

    protected function setUp(): void
    {

        // When tests are run together , there might be a conflict in case of login user from another test not
        // logged out before another user login is tested and errors may happen
        // Individually these tests may run fine
        // So users are logged out before testing

        parent::setUp();
        $this->browser()->visit('/logout');

        $this->createCustomerFixtures();
        $this->createCustomerFixturesB();
        $this->createEmployeeFixtures();
        $this->createProductFixtures();
        $this->createLocationFixtures();
        $this->createCurrencyFixtures($this->country);
        $this->createPriceFixtures($this->product1, $this->product2, $this->currency);


    }

}
