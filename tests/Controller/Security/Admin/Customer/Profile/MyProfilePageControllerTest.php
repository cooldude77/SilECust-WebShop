<?php

namespace Silecust\WebShop\Tests\Controller\Security\Admin\Customer\Profile;

use Silecust\WebShop\Service\Testing\Fixtures\CustomerFixture;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser\Test\HasBrowser;

class MyProfilePageControllerTest extends WebTestCase
{
    use HasBrowser, CustomerFixture;

    public function testProfile()
    {
        // Unauthenticated entry
        $uri = '/my/profile';

        $this->browser()->visit($uri)->assertNotAuthenticated();

        $this->createCustomerFixtures();

        $this->browser()
            ->use(function (KernelBrowser $browser) {

                $browser->loginUser($this->userForCustomer->object());
            })
            ->visit($uri)
            ->assertSuccessful();
    }
}
