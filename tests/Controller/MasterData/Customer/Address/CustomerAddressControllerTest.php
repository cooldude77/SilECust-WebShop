<?php

namespace Silecust\WebShop\Tests\Controller\MasterData\Customer\Address;

use Silecust\WebShop\Tests\Fixtures\CustomerFixture;
use Silecust\WebShop\Tests\Fixtures\EmployeeFixture;
use Silecust\WebShop\Tests\Fixtures\LocationFixture;
use Silecust\WebShop\Tests\Utility\SelectElement;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;

class CustomerAddressControllerTest extends WebTestCase
{

    use HasBrowser, EmployeeFixture, LocationFixture, SelectElement, CustomerFixture, Factories;

    protected function tearDown(): void
    {
        $this->browser()->visit('/logout');

    }

    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    /* public function testCreate()
     {


         $this->createCustomerFixtures();
         $this->createLocationFixtures();
         $this->createEmployeeFixtures();

         $uri = "/admin/customer/{$this->customer->getId()}/address/create";

         $this
             ->browser()
             ->visit($uri)
             ->assertNotAuthenticated()
             ->use(callback: function (Browser $browser) {
                 $browser->client()->loginUser($this->userForEmployee->object());
             })
             ->visit($uri)
             ->fillField('customer_address_create_form[line1]', 'line 1')
             ->fillField('customer_address_create_form[line2]', 'line 2')
             ->fillField('customer_address_create_form[line3]', 'line 3')
             ->use(function (Browser $browser) {
                 $this->addOption($browser, 'select', $this->postalCode->getId());
             }
             )->use(function (Browser $browser) {
                 $form = $browser->crawler()->filter('form')->form();

                 $y = $form->get('customer_address_create_form[postalCode]');
                 // $form->get('customer_address_create_form[postalCode]')->setValue(1);
                 //$form['customer_address_create_form[postalCode]']->select(1);
                 $form['customer_address_create_form[postalCode]']->disableValidation()->setValue(1);
                 $x = 0;
                 //   $form = $browser->crawler()->filter('form')->form();

             }
             )
             // ->fillField('customer_address_create_form[postalCode]', 1)
             ->fillField('customer_address_create_form[addressType]', 'shipping')
             ->fillField('customer_address_create_form[isDefault]', true)
             ->use(function (Browser $browser) {
                 //     $form = $browser->crawler()->filter('form')->form();
                 //  $form['customer_address_create_form[postalCode]']->select(1);

             }
             )
             ->click('Save')
             ->assertSuccessful();

         $created = CustomerAddressFactory::find(array('line1' => 'line 1'));

         $this->assertNotNull("First Name", $created);
     }
    */
}