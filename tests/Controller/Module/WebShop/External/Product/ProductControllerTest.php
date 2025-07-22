<?php /** @noinspection ALL */

namespace Silecust\WebShop\Tests\Controller\Module\WebShop\External\Product;

use Silecust\WebShop\Service\Module\WebShop\External\Cart\Product\Manager\CartProductManager;
use Silecust\WebShop\Service\Testing\Fixtures\CartFixture;
use Silecust\WebShop\Service\Testing\Fixtures\CurrencyFixture;
use Silecust\WebShop\Service\Testing\Fixtures\CustomerFixture;
use Silecust\WebShop\Service\Testing\Fixtures\LocationFixture;
use Silecust\WebShop\Service\Testing\Fixtures\OrderFixture;
use Silecust\WebShop\Service\Testing\Fixtures\PriceFixture;
use Silecust\WebShop\Service\Testing\Fixtures\ProductFixture;
use Silecust\WebShop\Service\Testing\Fixtures\SessionFactoryFixture;
use Silecust\WebShop\Service\Testing\Utility\FindByCriteria;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
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
        $this->createCustomerFixtures();
        $this->createProductFixtures();
        $this->createLocationFixtures();
        $this->createCurrencyFixtures($this->country);
        $this->createPriceFixtures($this->product1, $this->product2, $this->currency);

    }

    public function testListOfProducts()
    {
        $uri = '/';

        $this->browser()
            // don't allow cart when user is not logged in
            // not logged-in
            ->visit($uri)
            ->assertSee($this->product1->getDescription())
            ->assertSee($this->product2->getDescription())
            ->assertSee($this->priceProductBaseA->getPrice())
            ->assertSee($this->priceProductBaseB->getPrice())
            ->assertNotSee($this->productInactive->getDescription());

    }

    public function testAddToCart()
    {
        $this->createOrderFixturesA($this->customerA);

        $uriAddProductA = "/product/" . $this->product1->getName();

        // From the product page, click on add to cart button
        $this->browser()
            // don't allow cart when user is not logged in
            // not logged-in
            ->visit($uriAddProductA)
            ->assertNotAuthenticated()
            ->interceptRedirects()
            ->use(function (Browser $browser) {
                // log in User
                $browser->client()->loginUser($this->userForCustomerA->object());
            })
            ->interceptRedirects()
            ->visit($uriAddProductA)
            ->fillField(
                'cart_add_product_single_form[productId]', $this->product1->getId())
            ->fillField(
                'cart_add_product_single_form[quantity]', 1
            )
            ->click('button[name="addToCart"]')
            ->assertRedirectedTo('/cart')
            ->interceptRedirects()
            ->visit($uriAddProductA)
            ->fillField(
                'cart_add_product_single_form[productId]', $this->product1->getId())
            ->fillField(
                'cart_add_product_single_form[quantity]', 1
            )
            ->click('button[name="addToCart"]')
            ->use(function (KernelBrowser $browser) {

                $this->createSession($browser);
                $cart = $this->session->get(CartProductManager::CART_SESSION_KEY);

                // Test: Cart has right items and quantities
                $this->assertEquals(2, $cart[$this->product1->getId()]->quantity);
            })
            ->assertRedirectedTo('/cart');
    }

}
