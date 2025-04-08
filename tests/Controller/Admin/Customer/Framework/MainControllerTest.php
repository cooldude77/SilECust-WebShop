<?php

namespace Silecust\WebShop\Tests\Controller\Admin\Customer\Framework;

use Silecust\WebShop\Tests\Fixtures\CustomerFixture;
use Silecust\WebShop\Tests\Fixtures\EmployeeFixture;
use Silecust\WebShop\Tests\Fixtures\SuperAdminFixture;
use Silecust\WebShop\Tests\Utility\AuthenticateTestEmployee;
use Symfony\Bundle\FrameworkBundle\KernelBrowser as SymfonyBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser\Test\HasBrowser;

class MainControllerTest extends WebTestCase
{
    use HasBrowser, AuthenticateTestEmployee, EmployeeFixture, CustomerFixture, SuperAdminFixture;


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
                $kernelBrowser->loginUser($this->userForCustomer->object());
            })
            ->visit($uri)
            ->assertSuccessful()
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
}
