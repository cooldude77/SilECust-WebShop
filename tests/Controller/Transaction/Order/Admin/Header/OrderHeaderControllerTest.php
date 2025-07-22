<?php /** @noinspection ALL */

namespace Silecust\WebShop\Tests\Controller\Transaction\Order\Admin\Header;

use Silecust\WebShop\Factory\OrderJournalFactory;
use Silecust\WebShop\Factory\OrderStatusTypeFactory;
use Silecust\WebShop\Service\Testing\Fixtures\CurrencyFixture;
use Silecust\WebShop\Service\Testing\Fixtures\CustomerFixture;
use Silecust\WebShop\Service\Testing\Fixtures\EmployeeFixture;
use Silecust\WebShop\Service\Testing\Fixtures\LocationFixture;
use Silecust\WebShop\Service\Testing\Fixtures\OrderFixture;
use Silecust\WebShop\Service\Testing\Fixtures\OrderItemFixture;
use Silecust\WebShop\Service\Testing\Fixtures\PriceFixture;
use Silecust\WebShop\Service\Testing\Fixtures\ProductFixture;
use Silecust\WebShop\Service\Transaction\Order\Status\OrderStatusTypes;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;

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
        OrderItemFixture,
        Factories;


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
            ->assertNotSee($this->openOrderHeaderA->getGeneratedId())
            // others orders can be seen
            ->assertSee($this->afterPaymentSuccessOrderHeaderA->getGeneratedId())
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
        $uri = "/admin/order/{$this->openOrderHeaderA->getGeneratedId()}/display";

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
        $uri = "/admin/order/{$this->openOrderHeaderA->getGeneratedId()}/edit";

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

    public function testEditForNonOpenOrder()
    {
        $uri = "/admin/order/{$this->afterPaymentSuccessOrderHeaderA->getGeneratedId()}/edit";

        $statusType = OrderStatusTypeFactory::find(['type' => OrderStatusTypes::ORDER_SHIPPED]);

        $this
            ->browser()
            ->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)
            ->assertSuccessful()
            ->fillField('order_header_edit_form[orderStatusType]', $statusType->getId())
            ->fillField('order_header_edit_form[changeNote]', 'Order Shipped')
            ->click('Save')
            ->assertSuccessful();

        $journal = OrderJournalFactory::find(['orderHeader' => $this->afterPaymentSuccessOrderHeaderA]);


    }

    public function testAdminUrlEdit()
    {

        $uri = "/admin?_function=order&_type=edit&generatedId={$this->afterPaymentSuccessOrderHeaderA->getGeneratedId()}";

        $this
            ->browser()
            ->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)
            ->assertSuccessful();


    }

    public function testAdminUrlDisplay()
    {

        $uri = "/admin?_function=order&_type=display&generatedId={$this->afterPaymentSuccessOrderHeaderA->getGeneratedId()}";

        $this
            ->browser()
            ->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)
            ->assertSuccessful()
            ->click('Edit')
            ->assertSee('Edit Order');


    }

    protected function setUp(): void
    {

        $this->createCustomerFixtures();
        $this->createEmployeeFixtures();
        $this->createProductFixtures();
        $this->createLocationFixtures();
        $this->createCurrencyFixtures($this->country);
        $this->createPriceFixtures($this->productA, $this->productB, $this->currency);
        $this->createOrderFixturesA($this->customerA);
        $this->createOpenOrderItemsFixtureA($this->openOrderHeaderA, $this->productA, $this->productB);
        $this->createOpenOrderItemsFixtureA($this->afterPaymentSuccessOrderHeaderA, $this->productA, $this->productB);

    }

    protected function tearDown(): void
    {
        $this->browser()->visit('/logout');

    }


}
