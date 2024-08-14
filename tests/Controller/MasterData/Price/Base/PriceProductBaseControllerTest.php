<?php

namespace App\Tests\Controller\MasterData\Price\Base;

use App\Entity\PriceProductBase;
use App\Factory\PriceProductBaseFactory;
use App\Tests\Fixtures\CurrencyFixture;
use App\Tests\Fixtures\LocationFixture;
use App\Tests\Fixtures\PriceFixture;
use App\Tests\Fixtures\ProductFixture;
use App\Tests\Utility\FindByCriteria;
use App\Tests\Utility\SelectElement;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;

class PriceProductBaseControllerTest extends WebTestCase
{

    use HasBrowser, ProductFixture, SelectElement, CurrencyFixture, LocationFixture,
        PriceFixture, FindByCriteria;

    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testCreate()
    {

        $this->createProductFixtures();

        $this->createLocationFixtures();
        $this->createCurrencyFixtures($this->country);

        $createUrl = '/price/product/base/create';

        $this->browser()->visit($createUrl)
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


        $url = "price/product/base/{$this->priceProductBaseA->getId()}/edit";


        $this
            ->browser()
            ->visit($url)
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

        $url = "price/product/base/{$this->priceProductBaseA->getId()}/edit";

        $this->browser()->visit($url)->assertSuccessful();


    }


    public function testList()
    {

        $url = "price/product/base/list";
        $this->browser()->visit($url)->assertSuccessful();

    }

}
