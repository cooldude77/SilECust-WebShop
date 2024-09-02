<?php

namespace App\Tests\Controller\MasterData\Customer;

use App\Factory\CustomerFactory;
use App\Factory\SalutationFactory;
use App\Tests\Fixtures\CustomerFixture;
use App\Tests\Fixtures\EmployeeFixture;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;

class CustomerControllerTest extends WebTestCase
{

    use HasBrowser, EmployeeFixture, CustomerFixture;

    protected function tearDown(): void
    {
        $this->browser()->visit('/logout');

    }
    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testCreate()
    {
        $uri = '/admin/customer/create';

        $salutation = SalutationFactory::createOne(['name' => 'Mr.',
            'description' => 'Mister...']);
        $this->createEmployeeFixtures();

        $this
            ->browser()
            ->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)
            ->fillField(
                'customer_create_form[firstName]', 'First Name'
            )->fillField('customer_create_form[lastName]', 'Last Name'
            )->fillField('customer_create_form[email]', 'x@y.com')
            ->fillField('customer_create_form[phoneNumber]', '+91999999999')
            ->fillField('customer_create_form[plainPassword]', '4534geget355$%^')
            ->click('Save')
            ->assertSuccessful();

        $created = CustomerFactory::find(array('firstName' => 'First Name'));

        $this->assertEquals("First Name", $created->getFirstName());


    }

    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testEdit()
    {

        $salutation = SalutationFactory::createOne(['name' => 'Mr.',
            'description' => 'Mister...']);

        $this->createEmployeeFixtures();
        $this->createCustomerFixtures();

        $id = $this->customer->getId();

        $uri = "/admin/customer/$id/edit";

        $visit = $this->browser()->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)
            ->fillField(
                'customer_edit_form[firstName]', 'New First Name'
            )->fillField(
                'customer_edit_form[middleName]', 'New Middle Name'
            )
            ->fillField(
                'customer_edit_form[lastName]', 'New Last Name'
            )
            ->fillField('customer_edit_form[email]', 'f@g.com')
            ->fillField('customer_edit_form[phoneNumber]', '+9188888888')
            ->click('Save')->assertSuccessful();

        $created = CustomerFactory::find(array('firstName' => "New First Name"));

        $this->assertEquals("New First Name", $created->getFirstName());


    }

    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testDisplay()
    {

        $this->createEmployeeFixtures();
        $this->createCustomerFixtures();

        $id = $this->customer->getId();
        $uri = "/admin/customer/$id/display";

        $this->browser()
            ->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)
            ->assertSuccessful();


    }


    public function testList()
    {
        $this->createEmployeeFixtures();
        $uri = '/admin/customer/list';
        $this->browser()->visit($uri)->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)
            ->assertSuccessful();

    }


}
