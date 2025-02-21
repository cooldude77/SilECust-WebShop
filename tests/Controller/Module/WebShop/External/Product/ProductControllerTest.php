<?php

namespace Silecust\WebShop\Tests\Controller\Module\WebShop\External\Product;

use Silecust\WebShop\Tests\Fixtures\CartFixture;
use Silecust\WebShop\Tests\Fixtures\CurrencyFixture;
use Silecust\WebShop\Tests\Fixtures\CustomerFixture;
use Silecust\WebShop\Tests\Fixtures\LocationFixture;
use Silecust\WebShop\Tests\Fixtures\OrderFixture;
use Silecust\WebShop\Tests\Fixtures\PriceFixture;
use Silecust\WebShop\Tests\Fixtures\ProductFixture;
use Silecust\WebShop\Tests\Fixtures\SessionFactoryFixture;
use Silecust\WebShop\Tests\Utility\FindByCriteria;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;

class ProductControllerTest extends WebTestCase
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
    protected function setUp(): void
    {
        $this->browser()->visit('/logout');


    }

    public function testAddToCart()
    {
        $this->createCustomerFixtures();
        $this->createProductFixtures();
        $this->createLocationFixtures();
        $this->createCurrencyFixtures($this->country);
        $this->createPriceFixtures($this->productA, $this->productB, $this->currency);
        $this->createOpenOrderFixtures($this->customer);

        $uriAddProductA = "/cart/product/" . $this->productA->getId() . '/add';

        // From the product page, click on add to cart button
        $this->browser()
            // don't allow cart when user is not logged in
            // not logged-in
            ->visit($uriAddProductA)
            ->assertNotAuthenticated()
            ->interceptRedirects()
            ->use(function (Browser $browser) {
                // log in User
                $browser->client()->loginUser($this->userForCustomer->object());
            })
            ->interceptRedirects()
            ->visit($uriAddProductA)
            ->fillField(
                'cart_add_product_single_form[productId]', $this->productA->getId())
            ->fillField(
                'cart_add_product_single_form[quantity]', 1
            )
            ->click('button[name="addToCart"]')
            ->assertRedirectedTo('/cart');

    }


}
