<?php

namespace App\Tests\Controller\MasterData\Price\Base;

use App\Factory\CategoryFactory;
use App\Factory\CurrencyFactory;
use App\Factory\PriceProductBaseFactory;
use App\Factory\ProductFactory;
use App\Tests\Fixtures\ProductFixture;
use App\Tests\Utility\SelectElement;
use http\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;

class PriceProductBaseControllerTest extends WebTestCase
{

    use HasBrowser, ProductFixture, SelectElement;

    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testCreate()
    {

        $this->createProductFixtures();

        $currency = CurrencyFactory::createOne();
        $createUrl = '/price/product/base/create';

        $this->browser()->visit($createUrl)
            ->use(function (Browser $browser) use ($currency) {
                $this->addOption($browser,
                    'select[name="price_product_base_create_form[product]"]',
                    $this->productA->getId());

                $this->addOption($browser, 'select[name="price_product_base_create_form[currency]"]',
                    $currency->getId());

            })->fillField('price_product_base_create_form[product]', $this->productA->getId())
            ->fillField('price_product_base_create_form[currency]', $currency->getId())
            ->fillField('price_product_base_create_form[price]', 100)
            ->click('Save')
            ->assertSuccessful();

        $created = PriceProductBaseFactory::find(array('product' => $this->productA));

        $this->assertEquals(100, $created->getPrice());


    }

    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testEdit()
    {
        $category1 = CategoryFactory::createOne(['name' => 'Cat1',
                                                 'description' => 'Category 1']);

        $category2 = CategoryFactory::createOne(['name' => 'Cat2',
                                                 'description' => 'Category 2']);


        $product = ProductFactory::createOne(['category' => $category1]);

        $id = $product->getId();

        $url = "/product/$id/edit";

        $visit = $this->browser()->visit($url);

        $crawler = $visit->client()->getCrawler();

        $domDocument = $crawler->getNode(0)?->parentNode;

        $option = $domDocument->createElement('option');
        $option->setAttribute('value', $category1->getId());
        $selectElement = $crawler->filter('select')->getNode(0);
        $selectElement->appendChild($option);

        $option = $domDocument->createElement('option');
        $option->setAttribute('value', $category2->getId());
        $selectElement = $crawler->filter('select')->getNode(0);
        $selectElement->appendChild($option);

        $visit->fillField('product_edit_form[name]', 'Prod1')
            ->fillField(
                'product_edit_form[description]', 'Price 1'
            )
            ->fillField('product_edit_form[category]', $category2->getId())
            ->click('Save')
            ->assertSuccessful();

        $created = ProductFactory::find(array('name' => "Prod1"));

        $this->assertEquals("Prod1", $created->getName());


    }

    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testDisplay()
    {
        $category = CategoryFactory::createOne(['name' => 'Cat1',
                                                'description' => 'Category 1']);


        $product = ProductFactory::createOne(['category' => $category]);

        $id = $product->getId();
        $createUrl = "/product/$id/display";

        $this->browser()->visit($createUrl)->assertSuccessful();


    }


    public function testList()
    {

        $url = '/product/list';
        $this->browser()->visit($url)->assertSuccessful();

    }

}
