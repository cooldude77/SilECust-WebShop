<?php

namespace Silecust\WebShop\Tests\Controller\MasterData\Customer\Address;

use Silecust\WebShop\Factory\CustomerAddressFactory;
use Silecust\WebShop\Service\Testing\Fixtures\CustomerFixture;
use Silecust\WebShop\Service\Testing\Fixtures\EmployeeFixture;
use Silecust\WebShop\Service\Testing\Fixtures\LocationFixture;
use Silecust\WebShop\Service\Testing\Utility\SelectElement;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;

class CustomerAddressControllerTest extends WebTestCase
{

    use HasBrowser, EmployeeFixture, CustomerFixture, SelectElement, LocationFixture, Factories;


    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testCreateBothShippingAndBillingAddressesAndMarkBothAsDefault()
    {
        $uri = "/admin/customer/{$this->customer->getId()}/address/create";


        $this
            ->browser()
            ->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            // fill all remaining fields too
            ->visit($uri)
            ->use(function (Browser $browser) {
                $this->addOption($browser, 'select', $this->postalCode->getId());
            })
            ->fillField('customer_address_create_form[line1]', 'Line 1')
            ->fillField('customer_address_create_form[line2]', 'Line 2')
            ->fillField('customer_address_create_form[line3]', 'Line 3')
            ->checkField('The address is for shipping')
            ->checkField('The address is for billing')
            ->checkField('Use as default shipping')
            ->checkField('Use as default billing')
            ->fillField('customer_address_create_form[postalCode]', $this->postalCode->getId())
            ->click('Save')
            ->assertSuccessful();

        $created = CustomerAddressFactory::findBy(array('customer' => $this->customer));

        self::assertCount(2, $created);
        self::assertTrue($created[0]->isDefault());
        self::assertTrue($created[1]->isDefault());

    }

    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testCreateMultipleShippingAddressesAndMarkOneAsDefault()
    {
        $uri = "/admin/customer/{$this->customer->getId()}/address/create";

        $this
            ->browser()
            ->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            // fill all remaining fields too
            ->visit($uri)
            ->use(function (Browser $browser) {
                $this->addOption($browser, 'select', $this->postalCode->getId());
            })
            ->fillField('customer_address_create_form[line1]', 'Line 1')
            ->fillField('customer_address_create_form[line2]', 'Line 2')
            ->fillField('customer_address_create_form[line3]', 'Line 3')
            ->checkField('The address is for shipping')
            // ->checkField('The address is for billing')
            ->checkField('Use as default shipping')
            //->checkField('Use as default billing')
            ->fillField('customer_address_create_form[postalCode]', $this->postalCode->getId())
            ->click('Save')
            ->assertSuccessful();

        $this
            ->browser()
            // fill all remaining fields too
            ->visit($uri)
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)
            ->use(function (Browser $browser) {
                $reposne = $browser->client()->getResponse();
                $this->addOption($browser, 'select', $this->postalCode->getId());
            })
            ->fillField('customer_address_create_form[line1]', 'Line 111')
            ->fillField('customer_address_create_form[line2]', 'Line 22')
            ->fillField('customer_address_create_form[line3]', 'Line 33')
            ->checkField('The address is for shipping')
            // ->checkField('The address is for billing')
            ->checkField('Use as default shipping')
            //->checkField('Use as default billing')
            ->fillField('customer_address_create_form[postalCode]', $this->postalCode->getId())
            ->click('Save')
            ->assertSuccessful();
        $created1 = CustomerAddressFactory::find(array('line1' => 'Line 1'));
        $created2 = CustomerAddressFactory::find(array('line1' => 'Line 111'));

        self::assertFalse($created1->isDefault());
        self::assertTrue($created2->isDefault());
        

    }
    public function testCreateMultipleBillingAddressesAndMarkOneAsDefault()
    {
        $uri = "/admin/customer/{$this->customer->getId()}/address/create";

        $this
            ->browser()
            ->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            // fill all remaining fields too
            ->visit($uri)
            ->use(function (Browser $browser) {
                $this->addOption($browser, 'select', $this->postalCode->getId());
            })
            ->fillField('customer_address_create_form[line1]', 'Line 1')
            ->fillField('customer_address_create_form[line2]', 'Line 2')
            ->fillField('customer_address_create_form[line3]', 'Line 3')
            ->checkField('The address is for billing')
            // ->checkField('The address is for billing')
            ->checkField('Use as default billing')
            //->checkField('Use as default billing')
            ->fillField('customer_address_create_form[postalCode]', $this->postalCode->getId())
            ->click('Save')
            ->assertSuccessful();

        $this
            ->browser()
            // fill all remaining fields too
            ->visit($uri)
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)
            ->use(function (Browser $browser) {
                $reposne = $browser->client()->getResponse();
                $this->addOption($browser, 'select', $this->postalCode->getId());
            })
            ->fillField('customer_address_create_form[line1]', 'Line 111')
            ->fillField('customer_address_create_form[line2]', 'Line 22')
            ->fillField('customer_address_create_form[line3]', 'Line 33')
            ->checkField('The address is for billing')
            // ->checkField('The address is for billing')
            ->checkField('Use as default billing')
            //->checkField('Use as default billing')
            ->fillField('customer_address_create_form[postalCode]', $this->postalCode->getId())
            ->click('Save')
            ->assertSuccessful();
        $created1 = CustomerAddressFactory::find(array('line1' => 'Line 1'));
        $created2 = CustomerAddressFactory::find(array('line1' => 'Line 111'));

        self::assertFalse($created1->isDefault());
        self::assertTrue($created2->isDefault());
        

    }

   

    protected function setUp(): void
    {
        $this->createEmployeeFixtures();
        $this->createCustomerFixtures();
        $this->createLocationFixtures();
    }

    protected function tearDown(): void
    {
        $this->browser()->visit('/logout');

    }

}
