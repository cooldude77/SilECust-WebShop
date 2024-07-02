<?php

namespace App\Tests\Controller\Security\Admin\Customer\Profile;

use App\Tests\Fixtures\EmployeeFixture;
use App\Tests\Utility\AuthenticateTestEmployee;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser\Test\HasBrowser;

class MyProfilePageControllerTest extends WebTestCase
{
    use HasBrowser, EmployeeFixture;

    public function testProfile()
    {
        // Unauthenticated entry
        $uri = '/my/profile';

        $this->browser()->visit($uri)->assertNotAuthenticated();

        $this->createCustomer();

        $this->browser()
            ->use(function (KernelBrowser $browser) {

                $browser->loginUser($this->user->object());
            })
            ->visit($uri)
            ->assertSuccessful();
    }
}
