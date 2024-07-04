<?php

namespace App\Tests\Controller\Admin\Employee\FrameWork;

use App\Tests\Fixtures\EmployeeFixture;
use App\Tests\Utility\AuthenticateTestEmployee;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser\Test\HasBrowser;

class MainControllerTest extends WebTestCase
{
    use HasBrowser, AuthenticateTestEmployee, EmployeeFixture;

    public function testAdmin()
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
}