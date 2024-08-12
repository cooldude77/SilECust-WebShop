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
        $browser = $this->browser()->use(function (KernelBrowser $browser) {
            $browser->loginUser($this->userForEmployee->object());
        });

        $this->commonLinks($browser, $uri)
            ->interceptRedirects()
            ->visit($uri)
            ->assertNotSee("a#sidebar-link-employees");


    }

    private function commonLinks(\Zenstruck\Browser|\Zenstruck\Browser\KernelBrowser $browser,
        string $uri
    ) {

        return $browser->interceptRedirects()
            ->visit($uri)
            ->click('a#sidebar-link-category-list')
            ->followRedirect(1)
            ->interceptRedirects()
            ->visit($uri)
            ->click('a#sidebar-link-product-list')
            ->followRedirect(1)
            ->interceptRedirects()
            ->visit($uri)
            ->click('a#sidebar-link-price-product-base-list')
            ->followRedirect(1)
            ->interceptRedirects()
            ->visit($uri)
            ->click('a#sidebar-link-price-discount-list')
            ->followRedirect(1)
            ->interceptRedirects()
            ->visit($uri)
            ->click('a#sidebar-link-price-tax-list')
            ->followRedirect(1)
            ->interceptRedirects()
            ->visit($uri)
            ->click('a#sidebar-link-customer-list')
            ->followRedirect(1)
            ->interceptRedirects()
            ->visit($uri)
            ->click('a#sidebar-link-settings')
            ->followRedirect(1);

    }

    public function testAdminWithCustomer()
    {
        // Unauthenticated entry
        $uri = '/admin?_function=dashboard';

        $this->createCustomerFixtures();

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
        $browser = $this->browser()->use(function (KernelBrowser $browser) {
            $browser->loginUser($this->userForSuperAdmin->object());
        });

        $this->commonLinks($browser, $uri)
            ->interceptRedirects()
            ->visit($uri)
            ->click('a#sidebar-link-employee-list')
            ->followRedirect(1)
            ->assertSuccessful();
    }
}