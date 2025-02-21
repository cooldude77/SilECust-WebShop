<?php

namespace Silecust\WebShop\Tests\Controller\MasterData\Price\Discount;

use Silecust\WebShop\Entity\PriceProductDiscount;
use Silecust\WebShop\Tests\Fixtures\CurrencyFixture;
use Silecust\WebShop\Tests\Fixtures\EmployeeFixture;
use Silecust\WebShop\Tests\Fixtures\LocationFixture;
use Silecust\WebShop\Tests\Fixtures\PriceFixture;
use Silecust\WebShop\Tests\Fixtures\ProductFixture;
use Silecust\WebShop\Tests\Utility\FindByCriteria;
use Silecust\WebShop\Tests\Utility\SelectElement;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;

class PriceProductDiscountControllerTest extends WebTestCase
{

    use HasBrowser, ProductFixture, SelectElement, CurrencyFixture, LocationFixture,
        PriceFixture, FindByCriteria,EmployeeFixture;

    protected function setUp(): void
    {
        $this->createEmployeeFixtures();
    }
    protected function tearDown(): void
    {
        $this->browser()->visit('/logout');

    }
    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testCreate()
    {

        $this->createProductFixtures();

        $this->createLocationFixtures();
        $this->createCurrencyFixtures($this->country);

        $uri = '/admin/price/product/discount/create';

        $this->browser()->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)
            ->use(function (Browser $browser) {
                $this->addOption(
                    $browser,
                    'select[name="price_product_discount_create_form[product]"]',
                    $this->productA->getId()
                );

                $this->addOption(
                    $browser, 'select[name="price_product_discount_create_form[currency]"]',
                    $this->currency->getId()
                );

            })->fillField('price_product_discount_create_form[product]', $this->productA->getId())
            ->fillField('price_product_discount_create_form[currency]', $this->currency->getId())
            ->fillField('price_product_discount_create_form[value]', 500)
            ->click('Save')
            ->assertSuccessful();

        $created = $this->findOneBy(PriceProductDiscount::class, array('product' =>
                                                                           $this->productA->object(
                                                                           )));

        $this->assertEquals(500, $created->getValue());


    }

    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testEdit()
    {

        $this->createProductFixtures();

        $this->createLocationFixtures();
        $this->createCurrencyFixtures($this->country);
        $this->createPriceFixtures($this->productA, $this->productB, $this->currency);


        $uri = "/admin/price/product/discount/{$this->productDiscountA->getId()}/edit";


        $this
            ->browser()
            ->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)
            ->use(function (Browser $browser) {
                $this->addOption(
                    $browser,
                    'select[name="price_product_discount_edit_form[product]"]',
                    $this->productA->getId()
                );

                $this->addOption(
                    $browser, 'select[name="price_product_discount_edit_form[currency]"]',
                    $this->currency->getId()
                );


            })
            ->fillField('price_product_discount_edit_form[product]', $this->productA->getId())
            ->fillField(
                'price_product_discount_edit_form[currency]', $this->currency->getId()
            )
            ->fillField('price_product_discount_edit_form[value]', 200)
            ->click('Save')
            ->assertSuccessful();

        /** @var PriceProductDiscount $edited */
        $edited = $this->findOneBy(
            PriceProductDiscount::class, ['product' => $this->productA->object()]
        );
        $this->assertEquals(200, $edited->getValue());


    }

    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testDisplay()
    {

        $this->createProductFixtures();
        $this->createLocationFixtures();
        $this->createCurrencyFixtures($this->country);
        $this->createPriceFixtures($this->productA, $this->productB, $this->currency);

        $uri = "/admin/price/product/discount/{$this->productDiscountA->getId()}/edit";

        $this->browser()->visit($uri)->assertSuccessful();


    }


    public function testList()
    {

        $uri = '/admin/price/product/discount/list';
        $this->browser()->visit($uri)->assertSuccessful();

    }

}
