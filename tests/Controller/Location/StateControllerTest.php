<?php

namespace Silecust\WebShop\Tests\Controller\Location;

use Silecust\WebShop\Factory\StateFactory;
use Silecust\WebShop\Factory\CountryFactory;
use Silecust\WebShop\Tests\Fixtures\EmployeeFixture;
use Silecust\WebShop\Tests\Fixtures\LocationFixture;
use Silecust\WebShop\Tests\Utility\SelectElement;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;

class StateControllerTest extends WebTestCase
{

    use HasBrowser, LocationFixture, SelectElement, EmployeeFixture;

    protected function setUp(): void
    {
        $this->createEmployeeFixtures();
    }

    protected function tearDown(): void
    {
        $this->browser()->visit('/logout');

    }

    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testCreate()
    {
        $country = CountryFactory::createOne(['code' => 'IN', 'name' => 'India']);

        $uri = "/admin/state/country/{$country->getId()}/create";

        $visit = $this->browser()->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)
            ->use(function (Browser $browser) use ($country) {
                $this->addOption($browser, 'select', $country->getId());
            })
            ->fillField(
                'state_create_form[code]', 'KA'
            )->fillField(
                'state_create_form[name]', 'Karnataka'
            )
            ->click('Save')
            ->assertSuccessful();

        $created = StateFactory::find(array('code' => 'KA'));

        $this->assertEquals("Karnataka", $created->getName());


    }

    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testDisplay()
    {


        $id = $this->state->getId();
        $uri = "/admin/state/$id/display";

        $this->browser()->visit($uri)->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)->assertSuccessful();


    }


    public function testList()
    {
        $uri = "/admin/state/state/{$this->state->getId()}/list";
        $this->browser()->visit($uri)->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)->assertSuccessful();

    }

}
