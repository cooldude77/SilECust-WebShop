<?php /** @noinspection ALL */
/** @noinspection ALL */
/** @noinspection ALL */

/** @noinspection ALL */

namespace Silecust\WebShop\Tests\Controller\Admin\Customer\Address\Access;

use Silecust\WebShop\Service\Testing\Fixtures\CustomerAddressBFixture;
use Silecust\WebShop\Service\Testing\Fixtures\CustomerAddressFixture;
use Silecust\WebShop\Service\Testing\Fixtures\CustomerFixture;
use Silecust\WebShop\Service\Testing\Fixtures\CustomerFixtureB;
use Silecust\WebShop\Service\Testing\Fixtures\EmployeeFixture;
use Silecust\WebShop\Service\Testing\Fixtures\LocationFixture;
use Silecust\WebShop\Service\Testing\Utility\SelectElement;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;

/**
 * Test my/address access rules
 */
class CustomerAddressControllerTest extends WebTestCase
{

    use HasBrowser, EmployeeFixture, CustomerFixture, CustomerFixtureB, CustomerAddressFixture, CustomerAddressBFixture, SelectElement, LocationFixture, Factories;


    public function testEditShippingAddress()
    {

        $this->createCustomerAddressA($this->customerA);
        $uri = "/my/address/{$this->addressShippingA->getId()}/edit";

        $this
            ->browser()
            ->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForCustomerB->object());
            })
            // fill all remaining fields too
            ->visit($uri)
            ->expectException(AccessDeniedException::class);

    }

    public function testEditBillingAddress()
    {

        $this->createCustomerAddressA($this->customerA);
        $uri = "/my/address/{$this->addressShippingA->getId()}/edit";

        $this
            ->browser()
            ->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForCustomerB->object());
            })
            // fill all remaining fields too
            ->visit($uri)
            ->expectException(AccessDeniedException::class);

    }
  public function testDisplayShippingAddress()
    {

        $this->createCustomerAddressA($this->customerA);
        $uri = "/my/address/{$this->addressShippingA->getId()}/display";

        $this
            ->browser()
            ->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForCustomerB->object());
            })
            // fill all remaining fields too
            ->visit($uri)
            ->expectException(AccessDeniedException::class);

    }

    public function testDisplayBillingAddress()
    {

        $this->createCustomerAddressA($this->customerA);
        $uri = "/my/address/{$this->addressShippingA->getId()}/display";

        $this
            ->browser()
            ->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForCustomerB->object());
            })
            // fill all remaining fields too
            ->visit($uri)
            ->expectException(AccessDeniedException::class);

    }


    protected function setUp(): void
    {
        $this->createEmployeeFixtures();
        $this->createCustomerFixtures();
        $this->createCustomerFixturesB();
        $this->createLocationFixtures();
    }

    protected function tearDown(): void
    {
        $this->browser()->visit('/logout');

    }

}
