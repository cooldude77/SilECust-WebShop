<?php

namespace Silecust\WebShop\Tests\Controller\Module\WebShop\External\Shop;

use Silecust\WebShop\Service\Testing\Fixtures\CartFixture;
use Silecust\WebShop\Service\Testing\Fixtures\CurrencyFixture;
use Silecust\WebShop\Service\Testing\Fixtures\CustomerFixture;
use Silecust\WebShop\Service\Testing\Fixtures\LocationFixture;
use Silecust\WebShop\Service\Testing\Fixtures\OrderFixture;
use Silecust\WebShop\Service\Testing\Fixtures\PriceFixture;
use Silecust\WebShop\Service\Testing\Fixtures\ProductFixture;
use Silecust\WebShop\Service\Testing\Fixtures\SessionFactoryFixture;
use Silecust\WebShop\Service\Testing\Utility\FindByCriteria;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;

class MainControllerTest extends WebTestCase
{

    use HasBrowser,
        CurrencyFixture,
        CustomerFixture,
        ProductFixture,
        PriceFixture,
        LocationFixture,
        FindByCriteria,
        CartFixture,
        OrderFixture,
        SessionFactoryFixture,
        Factories;

    public function testWhenProductsExistButPriceDoesNotExist()
    {
        $this->createCustomerFixtures();
        $this->createProductFixtures();
        $this->createLocationFixtures();
        $this->createCurrencyFixtures($this->country);

        $this->browser()
            ->visit('/')
            ->assertSuccessful()
            ->assertSee('Base Price not found for product Prod name A')
            ->assertSee('Base Price not found for product Prod name B')
            // footer
            ->assertSee('Copyright @Silecust');

    }

    public function testWhenAnyProductDoesNotExist()
    {
        $this->browser()
            ->visit('/')
            ->assertSuccessful()
            /*     ->use(function (Browser $browser) {
                     $r = $browser->client()->getResponse();
                 })
              */
            ->assertSee('No products are available to list at the moment');

    }

    public
    function testShop()
    {

        // visit home , not logged in
        $this->browser()
            ->visit('/')
            ->assertSuccessful()
            ->assertSeeIn('title', 'Home Page');

    }

    protected function setUp(): void
    {
        $this->browser()->visit('/logout');


    }
}
