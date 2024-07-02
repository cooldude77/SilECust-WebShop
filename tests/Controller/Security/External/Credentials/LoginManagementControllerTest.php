<?php

namespace App\Tests\Controller\Security\External\Credentials;

use App\Tests\Fixtures\CustomerFixture;
use App\Tests\Fixtures\EmployeeFixture;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser\Test\HasBrowser;

class LoginManagementControllerTest extends WebTestCase
{
    use HasBrowser, CustomerFixture, EmployeeFixture;

    // password is converted into code in UserFactory method

    /**
     * @return void
     */
    public function testLoginAsCustomer()
    {
        $this->createCustomer();
        $uri = '/login';
        // user with customer
        $this->browser()
            // test: fill wrong creds
            ->visit($uri)
            ->fillField(
                '_username', $this->loginForCustomerInString
            )->fillField(
                '_password', 'Wrong Password'
            )
            ->interceptRedirects()
            ->click('login')
            ->assertNotAuthenticated()
            ->assertRedirectedTo('/login')
           // test: fill correct cred
            ->fillField(
                '_username', $this->loginForCustomerInString
            )->fillField(
                '_password', $this->passwordForCustomerInString
            )
            ->interceptRedirects()
            ->click('login')
            ->assertAuthenticated()
            ->assertRedirectedTo('/')
            // test: logoug
            ->visit('/logout')
            ->assertRedirectedTo('/')
            ->assertNotAuthenticated();
    }


    public function testLoginAsEmployee()
    {
        $this->createEmployee();
        $uri = '/login';
        // user with employee
        $this->browser()
            // test: fill wrong creds
            ->visit($uri)
            ->fillField(
                '_username', $this->loginForEmployeeInString
            )->fillField(
                '_password', 'Wrong Password'
            )
            ->interceptRedirects()
            ->click('login')
            ->assertNotAuthenticated()
            ->assertRedirectedTo('/login')
            // test: fill correct cred
            ->fillField(
                '_username', $this->loginForEmployeeInString
            )->fillField(
                '_password', $this->passwordForEmployeeInString
            )
            ->interceptRedirects()
            ->click('login')
            ->assertAuthenticated()
            ->assertRedirectedTo('/admin')
            // test: logoug
            ->visit('/logout')
            ->assertRedirectedTo('/')
            ->assertNotAuthenticated();

    }
}