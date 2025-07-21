<?php
/** @noinspection ALL */

namespace Silecust\WebShop\Tests\Controller\Module\WebShop\External\Cart;

use Silecust\WebShop\Entity\OrderHeader;
use Silecust\WebShop\Entity\OrderItem;
use Silecust\WebShop\Factory\OrderHeaderFactory;
use Silecust\WebShop\Factory\OrderItemFactory;
use Silecust\WebShop\Service\Module\WebShop\External\Cart\Product\Manager\CartProductManager;
use Silecust\WebShop\Service\Testing\Fixtures\CartFixture;
use Silecust\WebShop\Service\Testing\Fixtures\CurrencyFixture;
use Silecust\WebShop\Service\Testing\Fixtures\CustomerFixture;
use Silecust\WebShop\Service\Testing\Fixtures\LocationFixture;
use Silecust\WebShop\Service\Testing\Fixtures\PriceFixture;
use Silecust\WebShop\Service\Testing\Fixtures\ProductFixture;
use Silecust\WebShop\Service\Testing\Fixtures\SessionFactoryFixture;
use Silecust\WebShop\Service\Testing\Utility\FindByCriteria;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;

class CartControllerTest extends WebTestCase
{
    use HasBrowser,
        CurrencyFixture,
        CustomerFixture,
        ProductFixture,
        PriceFixture,
        LocationFixture,
        FindByCriteria,
        CartFixture,
        SessionFactoryFixture, Factories;

    public function testInCartProcesses()
    {

        $this->createCustomerFixtures();
        $this->createProductFixtures();
        $this->createLocationFixtures();
        $this->createCurrencyFixtures($this->country);
        $this->createPriceFixtures($this->productA, $this->productB, $this->currency);

        $cartUri = '/cart';

        $uriAddProductA = "/cart/product/" . $this->productA->getId() . '/add';
        $uriAddProductB = "/cart/product/" . $this->productB->getId() . '/add';

        $clearCartUri = '/cart/clear';

        $cartDeleteUri = "/cart/product/" . $this->productA->getId() . '/delete';

        // Test : just visit cart
        $this->browser()
            // todo: don't allow cart when user is not logged in

            ->interceptRedirects()
            ->visit($cartUri)
            ->assertRedirectedTo('/login')
            ->use(function (Browser $browser) {
                // log in User
                $browser->client()->loginUser($this->userForCustomerA->object());
            })

            // Test: Visit after login
            ->visit($cartUri)
            ->use(function (KernelBrowser $browser) {
                $this->createSession($browser);
                // Test : Cart got created
                $this->assertNotNull($this->session->get(CartProductManager::CART_SESSION_KEY));

                /** @var OrderHeader $order */
                $order = $this->findOneBy(
                    OrderHeader::class, ['customer' => $this->customerA->object()]
                );

                // Previously:
                // Test : An order should only be created when item is added to the cart
                // $this->assertNull($order);

                // Now:
                // Test : An order is created when cart is initialized
                $this->assertNotNull($order);

            })
            // Test: empty cart should not have clear cart button
            ->assertNotSee("Clear Cart")

            //Test :  add products to cart
            ->interceptRedirects()
            ->visit($uriAddProductA)
            ->fillField('cart_add_product_single_form[productId]', $this->productA->getId())
            ->fillField(
                'cart_add_product_single_form[quantity]', 1
            )
            ->click('button[name="addToCart"]')
            ->assertRedirectedTo('/cart', 1)
            ->use(function (Browser $browser) {

                // Now: Order is created when cart is loaded
                // Test : An order got created
                // $order = $this->findOneBy(
                //   OrderHeader::class, ['customer' => $this->customerA->object()]
                //);
                // self::assertNotNull($order);

                // $this->assertNotNull($order->getGeneratedId());
                $order = $this->findOneBy(OrderHeader::class, ['customer' => $this->customerA->object()]);
                // item got created
                $item = $this->findOneBy(OrderItem::class, ['orderHeader' => $order,
                        'product' => $this->productA->object()]
                );

                self::assertNotNull($item);
            })

            // Test : add another product
            ->visit($uriAddProductB)
            ->fillField('cart_add_product_single_form[productId]', $this->productB->getId())
            ->fillField(
                'cart_add_product_single_form[quantity]', 2
            )
            ->click('button[name="addToCart"]')
            ->use(function (Browser $browser) {

                // Test : An order got created
                $order = $this->findOneBy(
                    OrderHeader::class, ['customer' => $this->customerA->object()]
                );

                $item = $this->findOneBy(OrderItem::class, ['orderHeader' => $order,
                        'product' => $this->productB->object()]
                );

                $this->assertNotNull($item);
            })
            ->assertRedirectedTo('/cart', 1)

            // Test : visit cart after update
            ->visit($cartUri)
            // Test: update quantities
            ->fillField(
                'cart_multiple_entry_form[items][0][quantity]', 4
            )
            ->fillField(
                'cart_multiple_entry_form[items][1][quantity]', 6
            )
            ->click("Update Cart")
            ->use(function (\Zenstruck\Browser $browser) {

                $session = $browser->client()->getRequest()->getSession();
                $cart = $session->get(CartProductManager::CART_SESSION_KEY);

                // Test: Cart has right items and quantities
                $this->assertEquals(4, $cart[$this->productA->getId()]->quantity);
                $this->assertEquals(6, $cart[$this->productB->getId()]->quantity);

                $order = $this->findOneBy(
                    OrderHeader::class, ['customer' => $this->customerA->object()]
                );

                // Test : An order got created
                self::assertNotNull($order);
                $itemA = $this->findOneBy(OrderItem::class, ['orderHeader' => $order,
                        'product' => $this->productA->object()]
                );

                // Test : Order has right quantities
                $this->assertEquals(4, $itemA->getQuantity());

                $itemB = $this->findOneBy(OrderItem::class, ['orderHeader' => $order,
                        'product' => $this->productB->object()]
                );
                // Test : Order has right quantities
                $this->assertEquals(6, $itemB->getQuantity());

            })


            // Test: item delete from cart
            ->interceptRedirects()
            ->visit($cartDeleteUri)
            ->use(function (\Zenstruck\Browser $browser) {
                $session = $browser->client()->getRequest()->getSession();
                $cart = $session->get(CartProductManager::CART_SESSION_KEY);

                // Test: Product is removed from cart
                $this->assertTrue(empty($cart[$this->productA->getId()]));

                // Test : Other product still exists
                $this->assertTrue(isset($cart[$this->productB->getId()]));

                $order = $this->findOneBy(
                    OrderHeader::class, ['customer' => $this->customerA->object()]
                );

                $this->assertNotNull($order);
                $itemA = $this->findOneBy(OrderItem::class, ['orderHeader' => $order,
                        'product' => $this->productA->object()]
                );
                // Test : Item A got removed
                $this->assertNull($itemA);
                $itemB = $this->findOneBy(OrderItem::class, ['orderHeader' => $order,
                        'product' => $this->productB->object()]
                );
                // Test: Item B is still there
                $this->assertNotNull($itemB);

            })
            ->assertRedirectedTo($cartUri)
            // Test: clear cart
            ->interceptRedirects()
            ->visit($cartUri)
            ->click("Clear Cart")
            ->use(function (\Zenstruck\Browser $browser) {
                $session = $browser->client()->getRequest()->getSession();

                // Test: Cart is cleared
                $this->assertNull($session->get(CartProductManager::CART_SESSION_KEY));

                $order = $this->findOneBy(
                    OrderHeader::class, ['customer' => $this->customerA->object()]
                );
                $itemA = $this->findOneBy(OrderItem::class, ['orderHeader' => $order,
                        'product' => $this->productA->object()]
                );
                $this->assertNull($itemA);

                $itemB = $this->findOneBy(OrderItem::class, ['orderHeader' => $order,
                        'product' => $this->productB->object()]
                );

                $this->assertNull($itemB);


            })
            ->assertRedirectedTo('/');

    }

    public function testCartFillWhenUserLogsOutAndLogsInAgain()
    {

        $this->createCustomerFixtures();
        $this->createProductFixtures();
        $this->createLocationFixtures();
        $this->createCurrencyFixtures($this->country);
        $this->createPriceFixtures($this->productA, $this->productB, $this->currency);

        $cartUri = '/cart';

        $uriAddProductA = "/cart/product/" . $this->productA->getId() . '/add';
        $uriAddProductB = "/cart/product/" . $this->productB->getId() . '/add';


        // Test : just visit cart
        $this->browser()
            // todo: don't allow cart when user is not logged in
            ->use(function (Browser $browser) {
                // log in User
                $browser->client()->loginUser($this->userForCustomerA->object());
            })
            ->visit($cartUri)
            //Test :  add products to cart
            ->visit($uriAddProductA)
            ->fillField('cart_add_product_single_form[productId]', $this->productA->getId())
            ->fillField(
                'cart_add_product_single_form[quantity]', 1
            )
            ->click('button[name="addToCart"]')
            ->assertSuccessful()
            ->visit($uriAddProductB)
            ->fillField('cart_add_product_single_form[productId]', $this->productB->getId())
            ->fillField(
                'cart_add_product_single_form[quantity]', 2
            )
            ->click('button[name="addToCart"]')
            ->assertSuccessful()
            ->visit('/logout')
            ->assertNotAuthenticated();


        $this->browser()->visit('/login')
            ->fillField(
                '_username', $this->loginForCustomerAInString
            )->fillField(
                '_password', $this->passwordForCustomerAInString
            )
            ->click('login')
            ->followRedirects()
            ->assertAuthenticated()
            ->use(function (\Zenstruck\Browser $browser) {

                $session = $browser->client()->getRequest()->getSession();
                $cart = $session->get(CartProductManager::CART_SESSION_KEY);

                // Test: Cart has right items and quantities
                $this->assertEquals(1, $cart[$this->productA->getId()]->quantity);
                $this->assertEquals(2, $cart[$this->productB->getId()]->quantity);

            });

    }

    public function testCheckOutCart()
    {


        $this->createCustomerFixtures();
        $this->createProductFixtures();
        $this->createLocationFixtures();
        $this->createCurrencyFixtures($this->country);
        $this->createPriceFixtures($this->productA, $this->productB, $this->currency);

        $cartUri = '/cart';

        $uriAddProductA = "/cart/product/" . $this->productA->getId() . '/add';
        $uriAddProductB = "/cart/product/" . $this->productB->getId() . '/add';

        $browser = $this->browser()
            ->visit('/') // just to start the session
            ->use(function (Browser $browser) {
                // log in User
                $browser->client()->loginUser($this->userForCustomerA->object());

            })
            ->use(function (KernelBrowser $browser) {
                $this->createSession($browser);
                $this->createSessionKey($this->session);
                $this->addProductToCart($this->session, $this->productA->object(), 10);
            });

        $browser->visit($cartUri)
            ->interceptRedirects()
            ->click('Checkout')
            ->assertRedirectedTo('/checkout', 1);

    }

    public function testAddProductToCartTest()
    {

        $this->createCustomerFixtures();
        $this->createLocationFixtures();
        $this->createCurrencyFixtures($this->country);
        $this->createProductFixtures();
        $this->createPriceFixtures($this->productA, $this->productB, $this->currency);

        $uri = "/cart/product/" . $this->productA->getId() . '/add';


        $browser = $this
            ->browser()
            ->visit('/') // just to start the session
            ->use(function (Browser $browser) {
                // log in User
                $browser->client()->loginUser($this->userForCustomerA->object());
            })
            ->use(function (KernelBrowser $browser) {
                $this->createSession($browser);
                $this->createSessionKey($this->session);
            })
            ->visit($uri)
            ->fillField('cart_add_product_single_form[productId]', $this->productA->getId())
            ->fillField(
                'cart_add_product_single_form[quantity]', 1
            )
            ->click('button[name="addToCart"]')
            ->assertSuccessful()
            ->use(function (\Zenstruck\Browser $browser) {

                $orderHeader = OrderHeaderFactory::find(['customer' => $this->customerA]);
                $orderItems = OrderItemFactory::findBy(['orderHeader' => $orderHeader]);
                // check product searlized
                self::assertNotEmpty($orderItems[0]->getProductInJson());
                self::assertJson($orderItems[0]->getProductInJson());

            });;

    }

    protected function setUp(): void
    {
        $this->browser()->visit('/logout');


    }
}
