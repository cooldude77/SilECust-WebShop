<?php

namespace Silecust\WebShop\Tests\Controller\Admin\Employee\FrameWork;

use Silecust\WebShop\Tests\Fixtures\CustomerFixture;
use Silecust\WebShop\Tests\Fixtures\EmployeeFixture;
use Silecust\WebShop\Tests\Fixtures\SuperAdminFixture;
use Silecust\WebShop\Tests\Utility\AuthenticateTestEmployee;
use Symfony\Bundle\FrameworkBundle\KernelBrowser as SymfonyBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;

/**
 *
 */
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
    public function testAdminWithEmployee()
    {
        // Unauthenticated entry
        $uri = '/admin?_function=dashboard';
        $this->browser()->visit($uri)->assertNotAuthenticated();

        $this->createEmployeeFixtures();

        $this->browser()
            ->use(function (SymfonyBrowser $kernelBrowser) {
                $kernelBrowser->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)
            ->click('a#sidebar-link-category-list')
            ->assertOn('/admin', ['_function=category&_type=list'])
            ->visit($uri)
            ->click('a#sidebar-link-product-list')
            ->assertOn('/admin', ['_function=product&_type=list'])
            ->visit($uri)
            ->click('a#sidebar-link-price-product-base-list')
            ->assertOn('/admin', ['_function=price_product_base&_type=list'])
            ->visit($uri)
            ->click('a#sidebar-link-price-discount-list')
            ->assertOn('/admin', ['_function=price_product_discount&_type=list'])
            ->visit($uri)
            ->click('a#sidebar-link-price-tax-list')
            ->assertOn('/admin', ['_function=price_product_tax&_type=list'])
            ->visit($uri)
            ->click('a#sidebar-link-customer-list')
            ->assertOn('/admin', ['_function=customer&_type=list'])
            ->visit($uri)
            ->click('a#sidebar-link-settings')
            ->assertOn('/admin', ['_function=settings&_type=list'])
            ->visit($uri)
            ->click('a#link-dashboard')
            ->assertOn('/admin', ['_function=dashboard'])
            ->visit($uri)
            ->assertNotSee("a#sidebar-link-employees");


    }

    /**
     * @return void
     */
    public function testAdminWithCustomer()
    {
        // Unauthenticated entry
        $uri = '/admin?_function=dashboard';
       $this->createCustomerFixtures();

        // authenticate before visit
        $this->browser()->use(function (Browser $browser) {
            $browser->client()->loginUser($this->userForCustomer->object());
        })
            ->interceptRedirects()
            ->visit($uri)
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @return void
     */
    public function testSuperAdmin()
    {
        $uri = '/admin?_function=dashboard';

        $this->createSuperAdmin();

        // authenticate before visit
        $browser = $this->browser()->use(function (Browser $browser) {
            $browser->client()->loginUser($this->userForSuperAdmin->object());
        })
            ->visit($uri)
            ->click('a#sidebar-link-category-list')
            ->assertOn('/admin', ['_function=category&_type=list'])
            ->visit($uri)
            ->click('a#sidebar-link-product-list')
            ->assertOn('/admin', ['_function=product&_type=list'])
            ->visit($uri)
            ->click('a#sidebar-link-price-product-base-list')
            ->assertOn('/admin', ['_function=price_product_base&_type=list'])
            ->visit($uri)
            ->click('a#sidebar-link-price-discount-list')
            ->assertOn('/admin', ['_function=price_product_discount&_type=list'])
            ->visit($uri)
            ->click('a#sidebar-link-price-tax-list')
            ->assertOn('/admin', ['_function=price_product_tax&_type=list'])
            ->visit($uri)
            ->click('a#sidebar-link-customer-list')
            ->assertOn('/admin', ['_function=customer&_type=list'])
            ->visit($uri)
            ->click('a#sidebar-link-settings')
            ->assertOn('/admin', ['_function=settings&_type=list'])
            ->visit($uri)
            ->click('a#link-dashboard')
            ->assertOn('/admin', ['_function=dashboard'])
            ->visit($uri)
            ->click('a#sidebar-link-employee-list')
            ->assertOn('/admin', ['_function = employee & type=>list']);
    }
}