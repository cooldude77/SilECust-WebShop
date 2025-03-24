<?php

namespace Silecust\WebShop\Tests\Controller\Location;

use Silecust\WebShop\Factory\CityFactory;
use Silecust\WebShop\Factory\CountryFactory;
use Silecust\WebShop\Factory\StateFactory;
use Silecust\WebShop\Tests\Fixtures\EmployeeFixture;
use Silecust\WebShop\Tests\Fixtures\LocationFixture;
use Silecust\WebShop\Tests\Utility\SelectElement;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;

class CityControllerTest extends WebTestCase
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
        $state = StateFactory::createOne(['code' => 'KA', 'name' => 'Karnataka', 'country' => $country]);

        $uri = "/admin/state/{$state->getId()}/city/create";

        $this->browser()->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)
            ->use(function (Browser $browser) use ($state) {
                $this->addOption($browser, 'select', $state->getId());
            })
            ->fillField(
                'city_create_form[code]', 'BLR'
            )->fillField(
                'city_create_form[name]', 'Bangalore'
            )
            ->click('Save')
            ->assertSuccessful();

        $created = CityFactory::find(array('code' => 'BLR'));

        $this->assertEquals("Bangalore", $created->getName());


    }

    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testDisplay()
    {
        $country = CountryFactory::createOne(['code' => 'IN', 'name' => 'India']);
        $state = StateFactory::createOne(['code' => 'KA', 'name' => 'Karnataka', 'country' => $country]);
        $city = CityFactory::createOne(['code' => 'BLR', 'name' => 'Bangalore', 'state' => $state]);


        $uri = "/admin/city/{$city->getId()}/display";

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
        $city = CityFactory::createOne(['code' => 'BLR', 'name' => 'Bangalore', 'state' => $state]);

        $uri = "/admin/city/{$city->getId()}/edit";

        $this->browser()->visit($uri)->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)
            ->fillField(
                'city_edit_form[name]', 'Bangalore City'
            )
            ->click('Save')
            ->assertSuccessful();

        $state = CityFactory::find(array('code' => 'BLR'));

        $this->assertEquals("Bangalore City", $state->getName());


    }

    public function testList()
    {
        $country = CountryFactory::createOne(['code' => 'IN', 'name' => 'India']);
        $state1 = StateFactory::createOne(['code' => 'KA', 'name' => 'Karnataka', 'country' => $country]);
        $state2 = StateFactory::createOne(['code' => 'RJ', 'name' => 'Rajasthan', 'country' => $country]);
        CityFactory::createOne(['code' => 'BLR', 'name' => 'Bangalore', 'state' => $state1]);
        CityFactory::createOne(['code' => 'JPR', 'name' => 'Jaipur', 'state' => $state2]);

        $uri = "/admin/state/{$state1->getId()}/city/list";

        $this
            ->browser()
            ->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)->assertSuccessful();

        $uri = "/admin/state/{$state2->getId()}/city/list";

        $this->browser()
            ->visit($uri)
            ->visit($uri)
            ->assertSuccessful();

    }

}
