<?php

namespace App\Tests\Controller\Security\External\Credentials\Login;

use App\Tests\Fixtures\CustomerFixture;
use App\Tests\Fixtures\EmployeeFixture;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;

class LoginManagementControllerTest extends WebTestCase
{
    use HasBrowser, CustomerFixture, EmployeeFixture, Factories;

    // password is converted into code in UserFactory method


    /**
     * @return void
     */
    public function testLoginAsCustomer()
    {
        $this->createCustomerFixtures();
        $uri = '/login';
        // user with customer
        $br = $this->browser()
            // test: fill wrong creds
            ->visit($uri)
            ->fillField(
                '_username', $this->loginForCustomerInString
            )->fillField(
                '_password', 'Wrong Password'
            )
            ->click('login')
            ->assertNotAuthenticated()
            ->use(function (Browser $browser) {
                $respone = $browser->client()->getResponse();

            });


        // test: fill correct cred
        $br->fillField(
            '_username', $this->loginForCustomerInString
        )->fillField(
            '_password', $this->passwordForCustomerInString
        )
            ->interceptRedirects()
            ->click('login')
            ->assertAuthenticated()
            // test: redirected to home
            ->assertRedirectedTo('/')
            // test: logout
            ->visit('/logout')
            ->assertRedirectedTo('/')
            ->assertNotAuthenticated();
    }


    public function testLoginAsEmployee()
    {
        $this->createEmployeeFixtures();
        $uri = '/login';
        // user with employee
        $this->browser()
            // test: fill wrong creds
            ->visit($uri)
            ->fillField(
                '_username', $this->emailOfEmployeeInString
            )->fillField(
                '_password', 'Wrong Password'
            )
            ->click('login')
            ->assertNotAuthenticated()
            // test: fill correct cred
            ->fillField(
                '_username', $this->emailOfEmployeeInString
            )->fillField(
                '_password', $this->passwordForEmployeeInString
            )
            ->interceptRedirects()
            ->click('login')
            ->assertAuthenticated()
            // test: redirected to admin
            ->assertRedirectedTo('/admin?_function=dashboard')
            // test: logout
            ->visit('/logout')
            ->assertRedirectedTo('/')
            ->assertNotAuthenticated();

    }

    protected function setUp(): void
    {

        // When tests are run together , there might be a conflict in case of login user from another test not
        // logged out before another user login is tested and errors may happen
        // Individually these tests may run fine
        // So users are logged out before testing

        parent::setUp();
        $this->browser()->visit('/logout');

    }
}