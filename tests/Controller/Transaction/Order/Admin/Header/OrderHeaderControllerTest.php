<?php /** @noinspection ALL */

namespace App\Tests\Controller\Transaction\Order\Admin\Header;

use App\Tests\Fixtures\CurrencyFixture;
use App\Tests\Fixtures\CustomerFixture;
use App\Tests\Fixtures\EmployeeFixture;
use App\Tests\Fixtures\LocationFixture;
use App\Tests\Fixtures\OrderFixture;
use App\Tests\Fixtures\OrderItemFixture;
use App\Tests\Fixtures\PriceFixture;
use App\Tests\Fixtures\ProductFixture;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;

class OrderHeaderControllerTest extends WebTestCase
{

    use HasBrowser,
        EmployeeFixture,
        CustomerFixture,
        ProductFixture,
        PriceFixture,
        LocationFixture,
        CurrencyFixture,
        OrderFixture,
        OrderItemFixture;

    protected function setUp(): void
    {

        $this->createCustomerFixtures();
        $this->createEmployeeFixtures();
        $this->createProductFixtures();
        $this->createLocationFixtures();
        $this->createCurrencyFixtures($this->country);
        $this->createPriceFixtures($this->productA, $this->productB, $this->currency);
        $this->createOpenOrderFixtures($this->customer);
        $this->createOrderItemsFixture($this->openOrderHeader, $this->productA, $this->productB);
        $this->createOrderItemsFixture($this->afterPaymentSuccessOrderHeader, $this->productA, $this->productB);

    }

    protected function tearDown(): void
    {
        $this->browser()->visit('/logout');

    }

    public function testListShouldDisplayOnlyNotOpenOrders()
    {
        $uri = '/admin/order/list';

        $this
            ->browser()
            ->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)
            // open order should not be seen
            ->assertNotSee($this->openOrderHeader->getGeneratedId())
            // others orders can be seen
            ->assertSee($this->afterPaymentSuccessOrderHeader->getGeneratedId())
            ->assertSuccessful();
    }

    public function testCreate()
    {

        $uri = '/admin/order/create';

        $this
            ->browser()
            ->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)
            // test: orders cannot be created from admin panel for now
            ->assertStatus(404);

    }

    public function testDisplayForOpenOrderShouldNotBeAllowed()
    {
        $uri = "/admin/order/{$this->openOrderHeader->getGeneratedId()}/display";

        $this
            ->browser()
            ->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)
            // test: orders cannot be created from admin panel for now
            ->assertStatus(409);

    }

    public function testEditForOpenOrder()
    {
        $uri = "/admin/order/{$this->openOrderHeader->getGeneratedId()}/edit";

        $this
            ->browser()
            ->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)
            // test: orders cannot be created from admin panel for now
            ->assertStatus(409);

    }
}
