<?php

namespace App\Tests\Controller\Module\WebShop\External\Product;

use App\Tests\Fixtures\CartFixture;
use App\Tests\Fixtures\CurrencyFixture;
use App\Tests\Fixtures\CustomerFixture;
use App\Tests\Fixtures\LocationFixture;
use App\Tests\Fixtures\PriceFixture;
use App\Tests\Fixtures\ProductFixture;
use App\Tests\Fixtures\SessionFactoryFixture;
use App\Tests\Utility\FindByCriteria;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;

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
        SessionFactoryFixture;

    public function testAddToCart()
    {
        $this->createCustomerFixtures();
        $this->createProductFixtures();
        $this->createLocationFixtures();
        $this->createCurrencyFixtures($this->country);
        $this->createPriceFixtures($this->productA, $this->productB, $this->currency);

        $uriAddProductA = "/cart/product/" . $this->productA->getId() . '/add';

        // From the product page, click on add to cart button
        $this->browser()
            // todo: don't allow cart when user is not logged in
            // not logged-in
            ->interceptRedirects()
            ->visit($uriAddProductA)
            ->fillField('cart_add_product_single_form[productId]', $this->productA->getId())
            ->fillField(
                'cart_add_product_single_form[quantity]', 1
            )
            ->click('Add To Cart')
            ->assertRedirectedTo('/login')
            // now login
            ->use(callback: function (Browser $browser) {
                // log in User
                $browser->client()->loginUser($this->userForCustomer->object());
            })
            //Test :  add products to cart
            ->visit($uriAddProductA)
            ->fillField('cart_add_product_single_form[productId]', $this->productA->getId())
            ->fillField(
                'cart_add_product_single_form[quantity]', 1
            )
            ->click('Add To Cart')
            ->assertSuccessful();

    }


}
