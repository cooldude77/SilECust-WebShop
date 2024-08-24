<?php

namespace App\Tests\Controller\MasterData\Product\Product;

use App\Factory\CategoryFactory;
use App\Factory\ProductFactory;
use App\Tests\Fixtures\EmployeeFixture;
use App\Tests\Fixtures\ProductFixture;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;

class ProductControllerTest extends WebTestCase
{

    use HasBrowser, ProductFixture, EmployeeFixture;

    protected function setUp(): void
    {
        $this->createEmployee();
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
        $uri = '/admin/product/create';

        $this->browser()->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->post($uri,
                [
                    'body' => [
                        'product_create_form' => [
                            'name' => 'Prod1',
                            'description' => 'Product 1',
                            'category' => $this->categoryA->getId()
                        ],
                    ],
                ])
            ->assertSuccessful();

        $created = ProductFactory::find(array('name' => "Prod1"));

        $this->assertEquals("Prod1", $created->getName());
        $this->assertEquals("Product 1", $created->getDescription());


    }

    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testEdit()
    {

        $this->createProductFixtures();
        $uri = "/admin/product/{$this->productA->getId()}/edit";

        $visit = $this->browser()
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->post($uri,
                [
                    'body' => [
                        'product_edit_form' => [
                            'id' => $this->productA->getId(),
                            'name' => 'Prod11',
                            'description' => 'Product 11',
                            'category' => $this->categoryB->getId()
                        ],
                    ],
                ])
            ->assertSuccessful();

        $edited = ProductFactory::find($this->productA->getId());

        $this->assertEquals("Prod11", $edited->getName());
        $this->assertEquals("Product 11", $edited->getDescription());
        $this->assertEquals($this->categoryB->getId(), $edited->getCategory()->getId());


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
        $uri = "/admin/product/$id/display";

        $this->browser()->visit($uri)->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })->visit($uri)
            ->assertSuccessful();


    }


    public function testList()
    {

        $this->createProductFixtures();
        $uri = '/admin/product/list';
        $this->browser()->visit($uri)->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })->visit($uri)->assertSuccessful();

    }


}
