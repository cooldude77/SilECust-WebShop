<?php

namespace App\Tests\Command\Security\User;

use App\Command\Security\User\CustomerCreateCommand;
use App\Tests\Fixtures\CustomerFixture;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Console\Test\InteractsWithConsole;

class CustomerCreateCommandTest extends KernelTestCase
{

    use HasBrowser,InteractsWithConsole, CustomerFixture;


    public function testCreateSampleCustomer()
    {
        $this->executeConsoleCommand('silecust:customer:sample:create', [
            $this->customerEmailInString,
            $this->firstNameInString,
            $this->lastNameInString,
            $this->passwordForCustomerInString
        ])
            ->assertSuccessful(); // command exit code is 0
        $uri = '/login';
        // user with SuperAdmin
        $this->browser()
            // test: fill wrong creds
            ->visit($uri)
            // test: fill correct cred
            ->fillField(
                '_username', $this->customerEmailInString
            )->fillField(
                '_password', $this->passwordForCustomerInString
            )
            ->interceptRedirects()
            ->click('login')
            ->assertAuthenticated()
            // test: redirected to admin
            ->assertRedirectedTo('/');


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