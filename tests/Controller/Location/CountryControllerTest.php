<?php

namespace Silecust\WebShop\Tests\Controller\Location;

use Silecust\WebShop\Factory\CountryFactory;
use Silecust\WebShop\Tests\Fixtures\EmployeeFixture;
use Silecust\WebShop\Tests\Fixtures\LocationFixture;
use Silecust\WebShop\Tests\Utility\SelectElement;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;

class CountryControllerTest extends WebTestCase
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
        $uri = "/admin/country/create";

        $visit = $this->browser()->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)
            ->fillField(
                'country_create_form[code]', 'IN'
            )->fillField(
                'country_create_form[name]', 'India'
            )
            ->click('Save')
            ->assertSuccessful();

        $created = CountryFactory::find(array('code' => 'IN'));

        $this->assertEquals("India", $created->getName());


    }

    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testDisplay()
    {

        $country = CountryFactory::createOne(['code' => 'IN', 'name' => 'India']);

        $uri = "/admin/country/{$country->getId()}/display";

        $this->browser()->visit($uri)->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)
            ->assertSuccessful();


    }

    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testEdit()
    {

        $country = CountryFactory::createOne(['code' => 'IN', 'name' => 'India']);

        $uri = "/admin/country/{$country->getId()}/edit";

        $this->browser()->visit($uri)->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)
            ->fillField(
                'country_edit_form[name]', 'India-Country'
            )
            ->click('Save')
            ->assertSuccessful();

        $created = CountryFactory::find(array('code' => 'IN'));

        $this->assertEquals("India-Country", $created->getName());


    }


    public function testList()
    {
        $uri = "/admin/country/list";
        $this->browser()->visit($uri)->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)->assertSuccessful();

    }

}
