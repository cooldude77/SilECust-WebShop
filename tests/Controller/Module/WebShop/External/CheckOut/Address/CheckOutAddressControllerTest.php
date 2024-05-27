<?php

namespace App\Tests\Controller\Module\WebShop\External\CheckOut\Address;

use App\Factory\CustomerAddressFactory;
use App\Tests\Fixtures\CustomerFixture;
use App\Tests\Fixtures\LocationFixture;
use App\Tests\Utility\SelectElement;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;

class CheckOutAddressControllerTest extends WebTestCase
{
    use HasBrowser, CustomerFixture, LocationFixture, SelectElement;

    public function testCreateAddressBilling()
    {
        $this->createCustomer();
        $this->createLocationFixtures();

        $uri = "/checkout/address/create?id={$this->customer->getId()}&type=\"billing\"";
        $this
            ->browser()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->user->object());
            })
            ->visit($uri)
            ->use(function (Browser $browser) {
                $this->addOption($browser, 'select', $this->pinCode->getId());
            })
            ->fillField(
                'customer_address_create_form[line1]', 'Line 1'
            )
            ->fillField(
                'customer_address_create_form[line2]', 'Line 2'
            )
            ->fillField(
                'customer_address_create_form[line3]', 'Line 3'
            )
            ->fillField(
                'customer_address_create_form[pinCode]', $this->pinCode->getId()
            )
            ->fillField(
                'customer_address_create_form[addressType]', 'billing'
            )
            ->checkField(
                'customer_address_create_form[isDefault]'
            )
            ->click('Save')
            ->assertSuccessful();
        //todo: check redirect
    }

    public function testCreateAddressShipping()
    {
        $this->createCustomer();
        $this->createLocationFixtures();

        $uri = "/checkout/address/create?id={$this->customer->getId()}&type=\"shipping\"";
        $this
            ->browser()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->user->object());
            })
            ->visit($uri)
            ->use(function (Browser $browser) {
                $this->addOption($browser, 'select', $this->pinCode->getId());
            })
            ->fillField(
                'customer_address_create_form[line1]', 'Line 1'
            )
            ->fillField(
                'customer_address_create_form[line2]', 'Line 2'
            )
            ->fillField(
                'customer_address_create_form[line3]', 'Line 3'
            )
            ->fillField(
                'customer_address_create_form[pinCode]', $this->pinCode->getId()
            )
            ->fillField(
                'customer_address_create_form[addressType]', 'shipping'
            )
            ->checkField(
                'customer_address_create_form[isDefault]'
            )
            ->click('Save')
            ->assertSuccessful();
        //todo: check redirect
    }

    public function testChooseAddressesWhenNoAddressesPresent()
    {
        $this->createCustomer();
        $this->createLocationFixtures();


        $uri = "/checkout/addresses";
        $this
            ->browser()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->user->object());
            })
            ->visit($uri)
            ->assertSee("Add Billing Address")
            ->assertSee('Add Shipping Address');

        // one address is created already

        $address1 = CustomerAddressFactory::createOne(
            ['customer' => $this->customer, 'addressType' => 'shipping','line1'=>'A Good House']
        );

        $uri = "/checkout/addresses";
        $this
            ->browser()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->user->object());
            })
            ->visit($uri)
            ->use(callback: function (Browser $browser) {
                $respone = $browser->client()->getResponse();
            })
            ->assertContains($address1->getLine1())
            ->assertSee('Add Shipping Address');

    }
}