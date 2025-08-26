<?php /** @noinspection PhpPossiblePolymorphicInvocationInspection */

namespace Silecust\WebShop\Tests\Controller\Admin\Customer\Address;

use Silecust\WebShop\Service\Testing\Fixtures\CustomerAddressFixture;
use Silecust\WebShop\Service\Testing\Fixtures\CustomerFixture;
use Silecust\WebShop\Service\Testing\Fixtures\EmployeeFixture;
use Silecust\WebShop\Service\Testing\Fixtures\LocationFixture;
use Silecust\WebShop\Service\Testing\Utility\SelectElement;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;

/**
 * Test Urls related to admin of Customer Address
 */
class CustomerAddressControllerTest extends WebTestCase
{

    use HasBrowser, EmployeeFixture, CustomerFixture, CustomerAddressFixture, SelectElement, LocationFixture, Factories;


    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testCreateOwnAddress()
    {
        $uri = "/my/address/create";


        $this
            ->browser()
            ->visit('/')
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForCustomerA->object());
            })
            // fill all remaining fields too
            ->visit($uri)
            ->assertSee('Line1')
            ->assertSuccessful();


    }

    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testEditOwnAddress()
    {
        $this->createCustomerAddressA($this->customerA);

        $uri = "/my/address/{$this->addressShippingA->getId()}/edit";

        $this
            ->browser()
            ->visit('/')
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForCustomerA->object());
            })
            // fill all remaining fields too
            ->visit($uri)
            ->assertSee('Line1')
            ->assertSuccessful();


    }


    /**
     * @return void
     */
    public function testDeleteAddress()
    {

        $this->createCustomerAddressA($this->customerA);
        $address = $this->addressShippingA;

        $uri = "/my/address/{$this->addressShippingA->getId()}/delete";

        $this
            ->browser()
            ->visit('/')
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForCustomerA->object());
            })
            // fill all remaining fields too
            ->visit($uri)
            ->assertSuccessful();


    }


    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->createEmployeeFixtures();
        $this->createCustomerFixtures();
        $this->createLocationFixtures();
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        $this->browser()->visit('/logout');

    }

}
