<?php

namespace App\Tests\Controller\Module\WebShop\Admin;

use App\Factory\WebShopFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser\Test\HasBrowser;

class WebShopAdminControllerTest extends WebTestCase
{
    use HasBrowser;

    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.

    public function testCreate()
    {
        $createUrl = '/web-shop/create';

        $visit = $this->browser()->visit($createUrl)
            ->fillField('web_shop_create_form[name]', 'Ws1')->fillField(
                'web_shop_create_form[description]', 'WebShop 1'
            )
            ->click('Save')->assertSuccessful();

        $created = WebShopFactory::find(array('name' => "Ws1"));

        $this->assertEquals("Ws1", $created->getName());


    }

     public function testEdit()
    {


        $webShop = WebShopFactory::createOne();

        $id = $webShop->getId();

        $url = "/web-shop/$id/edit";

        $visit = $this->browser()->visit($url)
            ->fillField('web_shop_edit_form[name]', 'Ws1')
            ->fillField(
                'web_shop_edit_form[description]', 'WebShop 1'
            )
            ->click('Save')
            ->assertSuccessful();

        $created = WebShopFactory::find(array('name' => "Ws1"));

        $this->assertEquals("Ws1", $created->getName());


    }


    public function testDisplay()
    {


        $webShop = WebShopFactory::createOne();

        $id = $webShop->getId();
        $createUrl = "/web-shop/$id/display";

        $this->browser()->visit($createUrl)->assertSuccessful();


    }


    public function testList()
    {

        $url = '/web-shop/list';
        $this->browser()->visit($url)->assertSuccessful();

    }
    */
}
