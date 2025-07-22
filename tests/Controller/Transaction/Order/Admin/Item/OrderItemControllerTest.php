<?php

namespace Silecust\WebShop\Tests\Controller\Transaction\Order\Admin\Item;

use Silecust\WebShop\Factory\OrderJournalFactory;
use Silecust\WebShop\Service\Testing\Fixtures\CurrencyFixture;
use Silecust\WebShop\Service\Testing\Fixtures\CustomerFixture;
use Silecust\WebShop\Service\Testing\Fixtures\EmployeeFixture;
use Silecust\WebShop\Service\Testing\Fixtures\LocationFixture;
use Silecust\WebShop\Service\Testing\Fixtures\OrderFixture;
use Silecust\WebShop\Service\Testing\Fixtures\OrderItemFixture;
use Silecust\WebShop\Service\Testing\Fixtures\PriceFixture;
use Silecust\WebShop\Service\Testing\Fixtures\ProductFixture;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;

class OrderItemControllerTest extends WebTestCase
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
        PriceFixture,
        Factories;


    public function testEdit()
    {
        $uri = "/admin/order/item/{$this->orderItem1ForInProcessOrderA->getId()}/edit";

        $this
            ->browser()
            ->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());


            })
            ->visit($uri)
            ->assertSuccessful()
            ->fillField('order_item_edit_form[quantity]', 5)
            ->fillField('order_item_edit_form[changeNote]', 'Item Quantity Changed')
            ->click('Save')
            ->assertSuccessful();

        $journal = OrderJournalFactory::find(['orderHeader' => $this->inProcessOrderHeaderA]);

        self::assertNotNull($journal);

    }

    protected function setUp(): void
    {

        $this->createCustomerFixtures();
        $this->createEmployeeFixtures();
        $this->createProductFixtures();
        $this->createLocationFixtures();
        $this->createCurrencyFixtures($this->country);
        $this->createPriceFixtures($this->product1, $this->product2, $this->currency);
        $this->createOrderFixturesA($this->customerA);
        $this->createInProcessOrderItemsFixtureA($this->inProcessOrderHeaderA, $this->product1, $this->product2);
        $this->createPriceFixturesForItems($this->orderItem1ForInProcessOrderA, $this->orderItem2ForInProcessOrderA);


    }

    protected function tearDown(): void
    {
        $this->browser()->visit('/logout');

    }

}
