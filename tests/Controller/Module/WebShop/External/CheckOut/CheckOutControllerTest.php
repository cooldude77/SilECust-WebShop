<?php

namespace App\Tests\Controller\Module\WebShop\External\CheckOut;

use App\Factory\CustomerAddressFactory;
use App\Tests\Fixtures\CartFixture;
use App\Tests\Fixtures\CustomerFixture;
use App\Tests\Fixtures\LocationFixture;
use App\Tests\Fixtures\ProductFixture;
use App\Tests\Fixtures\SessionFactoryFixture;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;

class CheckOutControllerTest extends WebTestCase
{
    use HasBrowser, SessionFactoryFixture, ProductFixture,
        CustomerFixture, LocationFixture,
        CartFixture;

    public function testCheckout()
    {
        $this->createLocationFixtures();
        $this->createCustomer();
        $this->createProductFixtures();


        // without logging in
        // goto signup
        $uriCheckout = "/checkout";
        $this
            ->browser()
            ->visit($uriCheckout)
            ->assertOn('/signup?_redirect_upon_success_url=/checkout', ['query']);

        // user is logged in
        // cart is empty
        $this
            ->browser()
            ->use(function (Browser $browser) {
                $browser->client()->loginUser($this->userForCustomer->object());
            })
            ->use(function (KernelBrowser $browser){
                $this->createSession($browser);
                $this->createSessionKey($this->session);

            })
            ->interceptRedirects()
            ->visit($uriCheckout)
            ->assertRedirectedTo('/cart',1)
            // fill cart and see it redirected to addresses
            ->use(function (KernelBrowser $browser) {
                $this->addProductToCart($this->session,$this->productA->object(),10);

            })
            // addresses not created
            ->interceptRedirects()
            ->visit($uriCheckout)
            ->assertRedirectedTo('/checkout/addresses',1);

    }


}
