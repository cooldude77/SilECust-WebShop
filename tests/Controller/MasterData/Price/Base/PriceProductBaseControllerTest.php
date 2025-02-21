<?php

namespace Silecust\WebShop\Tests\Controller\MasterData\Price\Base;

use Silecust\WebShop\Entity\PriceProductBase;
use Silecust\WebShop\Factory\PriceProductBaseFactory;
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

class PriceProductBaseControllerTest extends WebTestCase
{

    use HasBrowser, ProductFixture, SelectElement, CurrencyFixture, LocationFixture, EmployeeFixture,
        PriceFixture, FindByCriteria;

    protected function setUp(): void
    {
        $this->browser()->visit('/logout');
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

        $uri = '/admin/price/product/base/create';

        $this->browser()->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)
            ->use(function (Browser $browser) {
                $this->addOption(
                    $browser,
                    'select[name="price_product_base_create_form[product]"]',
                    $this->productA->getId()
                );

                $this->addOption(
                    $browser, 'select[name="price_product_base_create_form[currency]"]',
                    $this->currency->getId()
                );

            })->fillField('price_product_base_create_form[product]', $this->productA->getId())
            ->fillField('price_product_base_create_form[currency]', $this->currency->getId())
            ->fillField('price_product_base_create_form[price]', 500)
            ->click('Save')
            ->assertSuccessful();

        $created = PriceProductBaseFactory::find(array('product' => $this->productA));

        $this->assertEquals(500, $created->getPrice());


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


        $uri ="/admin/price/product/base/{$this->priceProductBaseA->getId()}/edit";


        $this
            ->browser()
            ->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)
            ->use(function (Browser $browser) {

                $crawler = $browser->crawler();
                $domDocument = $crawler->getNode(0)?->parentNode;

                $option = $domDocument->createElement('option');
                $option->setAttribute('value', $this->productA->getId());

                $selectElement = $crawler->filter('[name="price_product_base_edit_form[product]"]')
                    ->getNode(0);
                $selectElement->appendChild($option);


            })
            ->use(function (Browser $browser) {
                $crawler = $browser->crawler();
                $domDocument = $crawler->getNode(0)?->parentNode;

                $option = $domDocument->createElement('option');
                $option->setAttribute('value', $this->currency->getId());

                $selectElement = $crawler->filter('[name="price_product_base_edit_form[currency]"]')
                    ->getNode(0);
                $selectElement->appendChild($option);
            })
            ->fillField('price_product_base_edit_form[product]', $this->productA->getId())
            ->fillField(
                'price_product_base_edit_form[currency]', $this->currency->getId()
            )
            ->fillField('price_product_base_edit_form[price]', 200)
            ->click('Save')
            ->assertSuccessful();

        /** @var PriceProductBase $edited */
        $edited = $this->findOneBy(PriceProductBase::class, ['product' => $this->productA->object()]
        );
        $this->assertEquals(200, $edited->getPrice());


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

        $uri = "/admin/price/product/base/{$this->priceProductBaseA->getId()}/edit";

        $this->browser()->visit($uri)->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)
            ->assertSuccessful();


    }


    public function testList()
    {

        $uri = '/admin/price/product/base/list';
        $this->browser()->visit($uri)->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)
            ->assertSuccessful();

    }

}
