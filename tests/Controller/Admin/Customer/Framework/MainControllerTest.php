<?php

namespace Silecust\WebShop\Tests\Controller\Admin\Customer\Framework;

use Silecust\WebShop\Factory\CustomerFactory;
use Silecust\WebShop\Service\Testing\Fixtures\CustomerFixture;
use Silecust\WebShop\Service\Testing\Fixtures\EmployeeFixture;
use Silecust\WebShop\Service\Testing\Fixtures\SuperAdminFixture;
use Silecust\WebShop\Service\Testing\Utility\AuthenticateTestEmployee;
use Symfony\Bundle\FrameworkBundle\KernelBrowser as SymfonyBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;

class MainControllerTest extends WebTestCase
{
    use HasBrowser, AuthenticateTestEmployee, EmployeeFixture, CustomerFixture, SuperAdminFixture, Factories;


    protected function setUp(): void
    {

        // When tests are run together , there might be a conflict in case of login user from another test not
        // logged out before another user login is tested and errors may happen
        // Individually these tests may run fine
        // So users are logged out before testing

        parent::setUp();
        $this->browser()->visit('/logout');

    }

    /**
     * @return void
     */
    public function testDashboardWithCustomer()
    {
        // Unauthenticated entry
        $uri = '/my';
        $this->browser()->visit($uri)->assertNotAuthenticated();

        $this->createCustomerFixtures();

        $this->browser()
            ->use(function (SymfonyBrowser $kernelBrowser) {
                $kernelBrowser->loginUser($this->userForCustomerA->object());
            })
            ->visit($uri)
            ->assertSuccessful()
            // footer
            ->assertSee('Copyright @Silecust')
            ->visit($uri)
            ->click('a#sidebar-link-my-order-list')
            ->assertSuccessful()
            ->visit($uri)
            ->click('a#sidebar-link-my-addresses-list')
            ->assertSuccessful()
            ->visit($uri)
            ->click('a#sidebar-link-my-personal-info')
            ->assertSuccessful();


    }


    /**
     * @return void
     */
    public function testMyUrlsWithoutLogin()
    {

        $this->createCustomerFixtures();

        $this
            ->browser()
            ->visit('/my/dashboard')
            ->assertNotAuthenticated()
            ->visit('/my')
            ->assertNotAuthenticated()
            ->visit('/my/profile')
            ->assertNotAuthenticated()
            ->visit('/my/orders')
            ->assertNotAuthenticated()
            ->visit('/my/addresses')
            ->assertNotAuthenticated()
            ->visit('/my/address/create')
            ->assertNotAuthenticated()
            ->visit('/my/orders/1/display')
            ->assertNotAuthenticated()
            ->visit('/my/orders/items/1/display')
            ->assertNotAuthenticated();

    }

    public function testEditPersonalData()
    {
        $this->createCustomerFixtures();


        $uri = "/my/personal-info";

        $this->browser()->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForCustomerA->object());
            })
            ->interceptRedirects()
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
            ->click('Save')
            ->assertRedirectedTo("/my/personal-info");

        $created = CustomerFactory::find(array('firstName' => "New First Name"));

        $this->assertEquals("New First Name", $created->getFirstName());


    }
}
