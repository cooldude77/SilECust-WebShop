<?php

namespace App\Tests\Controller\Admin\Employee\FrameWork;

use App\Tests\Fixtures\CustomerFixture;
use App\Tests\Fixtures\EmployeeFixture;
use App\Tests\Fixtures\SuperAdminFixture;
use App\Tests\Utility\AuthenticateTestEmployee;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Browser\Test\HasBrowser;

class MainControllerTest extends WebTestCase
{
    use HasBrowser, AuthenticateTestEmployee, EmployeeFixture, CustomerFixture, SuperAdminFixture;

    public function testAdminWithEmployee()
    {
        // Unauthenticated entry
        $uri = '/admin?_function=dashboard';
        $this->browser()->visit($uri)->assertNotAuthenticated();

        $this->createEmployee();

        // authenticate before visit
        $this->browser()->use(function (KernelBrowser $browser) {
            $browser->loginUser($this->userForEmployee->object());
        })
            ->visit($uri)
            ->click("a#admin-panel-home-url")
            ->followRedirects()
            ->click('a#sidebar-link-category-list')
            ->followRedirects()
            ->assertSuccessful()
            ->visit($uri)
            ->click('a#sidebar-link-product-list')
            ->followRedirects()
            ->assertSuccessful()
            ->visit($uri)
            ->click('a#sidebar-link-product-type-list')
            ->followRedirects()
            ->assertSuccessful()
            ->visit($uri)
            ->click('a#sidebar-link-product-attribute-list')
            ->followRedirects()
            ->assertSuccessful()
            ->visit($uri)
            ->click('a#sidebar-link-customer-list')
            ->followRedirects()
            ->assertSuccessful()
            ->visit($uri)
            ->click('a#sidebar-link-web-shop-list')
            ->followRedirects()
            ->assertSuccessful()
            ->visit($uri)
            ->click('a#sidebar-link-settings')
            ->followRedirects()
            ->assertSuccessful();

        //todo: intercept redirects
        // todo: check for country/city/state/postal code

    }

    public function testAdminWithCustomer()
    {
        // Unauthenticated entry
        $uri = '/admin?_function=dashboard';

        $this->createCustomer();

        // authenticate before visit
        $this->browser()->use(function (KernelBrowser $browser) {
            $browser->loginUser($this->userForCustomer->object());
        })
            ->interceptRedirects()
            ->visit($uri)
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }


    public function testSuperAdmin()
    {
        $uri = '/admin?_function=dashboard';

        $this->createSuperAdmin();

        // authenticate before visit
        $this->browser()->use(function (KernelBrowser $browser) {
            $browser->loginUser($this->userForSuperAdmin->object());
        })
            ->visit($uri)
            ->assertAuthenticated()
            ->assertSuccessful();
    }
}