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
use Silecust\WebShop\Service\Testing\Utility\DieHere;
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
        $uri = "/admin/order/item/{$this->orderItemAForInProcessOrder->getId()}/edit";

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

        $journal = OrderJournalFactory::find(['orderHeader' => $this->inProcessOrderHeader]);

        self::assertNotNull($journal);

    }

    protected function setUp(): void
    {

        $this->createCustomerFixtures();
        $this->createEmployeeFixtures();
        $this->createProductFixtures();
        $this->createLocationFixtures();
        $this->createCurrencyFixtures($this->country);
        $this->createPriceFixtures($this->productA, $this->productB, $this->currency);
        $this->createOrderFixtures($this->customerA);
        $this->createInProcessOrderItemsFixture($this->inProcessOrderHeader, $this->productA, $this->productB);
        $this->createPriceFixturesForItems($this->orderItemAForInProcessOrder, $this->orderItemBForInProcessOrder);


    }

    protected function tearDown(): void
    {
        $this->browser()->visit('/logout');

    }

}
