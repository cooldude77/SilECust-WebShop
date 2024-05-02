<?php

namespace App\Tests\Controller\Admin\Product\Product;

use App\Factory\CategoryFactory;
use App\Factory\ProductFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser\Test\HasBrowser;

class ProductControllerTest extends WebTestCase
{

    use HasBrowser;

    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testCreate()
    {
        $category = CategoryFactory::createOne(['name'=>'Cat1',
                                                'description'=>'Category 1']);

        $id = $category->getId();
        $createUrl = '/product/create';

        $visit = $this->browser()->visit($createUrl);

        $crawler = $visit->client()->getCrawler();

        $domDocument = $crawler->getNode(0)?->parentNode;

        $option = $domDocument->createElement('option');
        $option->setAttribute('value', $category->getId());
        $selectElement = $crawler->filter('select')->getNode(0);
        $selectElement->appendChild($option);

        $visit->fillField('product_create_form[name]', 'Prod1')
            ->fillField(
                'product_create_form[description]', 'Product 1'
            )
            ->fillField('product_create_form[category]', $id)
            ->click('Save')
            ->assertSuccessful();

        $created = ProductFactory::find(array('name'=>"Prod1"));

        $this->assertEquals("Prod1", $created->getName());


    }

  public function testList()
    {

        $url = '/product/list';
        $this->browser()->visit($url)->assertSuccessful();

    }


}
