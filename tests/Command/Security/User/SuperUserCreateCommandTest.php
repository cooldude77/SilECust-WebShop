<?php

namespace App\Tests\Command\Security\User;

use App\Tests\Fixtures\SuperAdminFixture;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Console\Test\InteractsWithConsole;

class SuperUserCreateCommandTest extends KernelTestCase
{

    use HasBrowser, InteractsWithConsole, SuperAdminFixture;


    public function testCreateSuperUser()
    {
        $this->executeConsoleCommand('silecust:user:super:create', [
            $this->emailOfSuperAdminInString,
            $this->firstNameOfSuperAdminInString,
            $this->lastNameOfSuperAdminInString,
            $this->passwordForSuperAdminInString
        ])
            ->assertSuccessful();

        $uri = '/login';
        // user with SuperAdmin
        $this->browser()
            // test: fill wrong creds
            ->visit($uri)
            // test: fill correct cred
            ->fillField(
                '_username', $this->emailOfSuperAdminInString
            )->fillField(
                '_password', $this->passwordForSuperAdminInString
            )
            ->interceptRedirects()
            ->click('login')
            ->assertAuthenticated()
            // test: redirected to admin
            ->assertRedirectedTo('/admin?_function=dashboard');


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
