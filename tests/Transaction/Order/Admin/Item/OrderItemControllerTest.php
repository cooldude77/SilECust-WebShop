<?php

namespace App\Tests\Transaction\Order\Admin\Item;

use App\Entity\OrderItem;
use App\Tests\Fixtures\CurrencyFixture;
use App\Tests\Fixtures\CustomerFixture;
use App\Tests\Fixtures\LocationFixture;
use App\Tests\Fixtures\OrderFixture;
use App\Tests\Fixtures\OrderItemFixture;
use App\Tests\Fixtures\PriceFixture;
use App\Tests\Fixtures\ProductFixture;
use App\Tests\Utility\FindByCriteria;
use App\Tests\Utility\SelectElement;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;

class OrderItemControllerTest extends WebTestCase
{

    use HasBrowser, FindByCriteria, ProductFixture, CustomerFixture, PriceFixture, SelectElement,
        OrderFixture, OrderItemFixture, LocationFixture, CurrencyFixture;

    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testCreate()
    {

        $this->createCustomerFixtures();
        $this->createOpenOrderFixtures($this->customer);
        $this->createProductFixtures();
        $this->createLocationFixtures();
        $this->createCurrencyFixtures($this->country);
        $this->createPriceFixtures($this->productA, $this->productB, $this->currency);


        $createUrl = "/order/{$this->orderHeader->getId()}/item/create";

        $this->browser()->visit($createUrl)->use(function (Browser $browser) {
            $this->addOption(
                $browser, 'select[name="order_item_create_form[product]"]',
                $this->productA->getId()
            );

        })->fillField('order_item_create_form[product]', $this->customer->getId())
            ->fillField('order_item_create_form[quantity]', 4)
            ->click('Save')
            ->assertSuccessful();

        $created = $this->findOneBy(OrderItem::class, ['product' => $this->productA->object()]);

        self::assertNotNull($created);

    }

    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testEdit()
    {

        $this->createCustomerFixtures();
        $this->createOpenOrderFixtures($this->customer);
        $this->createProductFixtures();
        $this->createLocationFixtures();
        $this->createCurrencyFixtures($this->country);
        $this->createPriceFixtures($this->productA, $this->productB, $this->currency);
        $this->createOrderItemsFixture($this->orderHeader, $this->productA, $this->productB);


        $createUrl = "/order/item/{$this->orderHeader->getId()}/edit";


        $this->browser()->visit($createUrl)
            ->fillField('order_item_edit_form[quantity]', 200)
            ->click('Save')
            ->assertSuccessful();

        /** @var OrderItem $created */
        $created = $this->findOneBy(OrderItem::class, ['product' => $this->productA->object()]);

        self::assertEquals(200, $created->getQuantity());

    }


    public function testList()
    {
        $this->createCustomerFixtures();
        $this->createOpenOrderFixtures($this->customer);
        $this->createProductFixtures();
        $this->createLocationFixtures();
        $this->createCurrencyFixtures($this->country);
        $this->createPriceFixtures($this->productA, $this->productB, $this->currency);

        $url = "order/item/list";
        $this->browser()->visit($url)->assertSuccessful();

    }

}
