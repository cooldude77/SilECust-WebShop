<?php /** @noinspection PhpPossiblePolymorphicInvocationInspection */

namespace Silecust\WebShop\Tests\Controller\MasterData\Customer\Address;

use Silecust\WebShop\Factory\CustomerAddressFactory;
use Silecust\WebShop\Service\Testing\Fixtures\CustomerAddressFixture;
use Silecust\WebShop\Service\Testing\Fixtures\CustomerFixture;
use Silecust\WebShop\Service\Testing\Fixtures\EmployeeFixture;
use Silecust\WebShop\Service\Testing\Fixtures\LocationFixture;
use Silecust\WebShop\Service\Testing\Utility\SelectElement;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;

/**
 * Test Urls related to admin of Customer Address
 */
class CustomerAddressControllerTest extends WebTestCase
{

    use HasBrowser, EmployeeFixture, CustomerFixture, CustomerAddressFixture, SelectElement, LocationFixture, Factories;


    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testCreateBothShippingAndBillingAddressesAndMarkBothAsDefault()
    {
        $uri = "/admin/customer/{$this->customerA->getId()}/address/create";


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

        $created = CustomerAddressFactory::findBy(array('customer' => $this->customerA));

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
        $uri = "/admin/customer/{$this->customerA->getId()}/address/create";

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

    /**
     * @return void
     */
    public function testCreateMultipleBillingAddressesAndMarkOneAsDefault()
    {
        $uri = "/admin/customer/{$this->customerA->getId()}/address/create";

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

    /**
     * @return void
     */
    public function testEditShippingAddress()
    {

        $this->createCustomerAddressA($this->customerA);
        $uri = "/admin/customer/address/{$this->addressShippingA->getId()}/edit";

        $this
            ->browser()
            ->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            // fill all remaining fields too
            ->visit($uri)
            ->fillField('customer_address_edit_form[line1]', 'Line 1')
            ->fillField('customer_address_edit_form[line2]', 'Line A')
            ->fillField('customer_address_edit_form[line3]', 'Line B')
            ->checkField('Use as default shipping')
            ->click('Save')
            ->assertSuccessful();

        $created = CustomerAddressFactory::find(array('line1' => 'Line 1'));

        self::assertTrue($created->isDefault());

    }

    /**
     * @return void
     */
    public function testEditBillingAddress()
    {

        $this->createCustomerAddressA($this->customerA);
        $uri = "/admin/customer/address/{$this->addressBillingA->getId()}/edit";

        $this
            ->browser()
            ->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            // fill all remaining fields too
            ->visit($uri)
            ->fillField('customer_address_edit_form[line1]', 'Line 1')
            ->fillField('customer_address_edit_form[line2]', 'Line A')
            ->fillField('customer_address_edit_form[line3]', 'Line B')
            ->checkField('Use as default billing')
            ->click('Save')
            ->assertSuccessful();

        $created = CustomerAddressFactory::find(array('line1' => 'Line 1'));

        self::assertTrue($created->isDefault());


    }


    /**
     * @return void
     */
    public function testDeleteAddress()
    {

        $this->createCustomerAddressA($this->customerA);
        $address = $this->addressShippingA;

        $uri = "/admin/customer/address/{$this->addressShippingA->getId()}/delete";

        $this
            ->browser()
            ->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            // fill all remaining fields too
            ->visit($uri)
            ->assertSuccessful();

        self::assertCount(0, CustomerAddressFactory::findBy(['id' => $address->getId()]));

    }


    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->createEmployeeFixtures();
        $this->createCustomerFixtures();
        $this->createLocationFixtures();
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        $this->browser()->visit('/logout');

    }

}
