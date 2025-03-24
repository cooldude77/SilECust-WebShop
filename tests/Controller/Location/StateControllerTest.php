<?php

namespace Silecust\WebShop\Tests\Controller\Location;

use Silecust\WebShop\Factory\CountryFactory;
use Silecust\WebShop\Factory\StateFactory;
use Silecust\WebShop\Tests\Fixtures\EmployeeFixture;
use Silecust\WebShop\Tests\Fixtures\LocationFixture;
use Silecust\WebShop\Tests\Utility\SelectElement;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;

class StateControllerTest extends WebTestCase
{

    use HasBrowser, LocationFixture, SelectElement, EmployeeFixture, Factories;

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

        $country = CountryFactory::createOne(['code' => 'IN', 'name' => 'India']);
        $state = StateFactory::createOne(['code' => 'KA', 'name' => 'Karnataka', 'country' => $country]);


        $uri = "/admin/state/{$state->getId()}/display";

        $this->browser()->visit($uri)->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)
            ->assertSuccessful();


    }

    public function testEdit()
    {

        $country = CountryFactory::createOne(['code' => 'IN', 'name' => 'India']);
        $state = StateFactory::createOne(['code' => 'KA', 'name' => 'Karnataka', 'country' => $country]);

        $uri = "/admin/state/{$state->getId()}/edit";

        $this->browser()->visit($uri)->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)
            ->fillField(
                'state_edit_form[name]', 'Karnataka State'
            )
            ->click('Save')
            ->assertSuccessful();

        $state = StateFactory::find(array('code' => 'KA'));

        $this->assertEquals("Karnataka State", $state->getName());


    }

    public function testList()
    {
        $country = CountryFactory::createOne(['code' => 'IN', 'name' => 'India']);
        StateFactory::createOne(['code' => 'KA', 'name' => 'Karnataka', 'country' => $country]);
        StateFactory::createOne(['code' => 'RJ', 'name' => 'Rajasthan', 'country' => $country]);

        $uri = "/admin/state/country/{$country->getId()}/list";

        $this->browser()->visit($uri)->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)->assertSuccessful();

    }

}
