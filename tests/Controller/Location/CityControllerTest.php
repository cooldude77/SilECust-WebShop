<?php

namespace Silecust\WebShop\Tests\Controller\Location;

use Silecust\WebShop\Factory\CityFactory;
use Silecust\WebShop\Tests\Fixtures\EmployeeFixture;
use Silecust\WebShop\Tests\Fixtures\LocationFixture;
use Silecust\WebShop\Tests\Utility\SelectElement;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;

class CityControllerTest extends WebTestCase
{

    use HasBrowser, LocationFixture, SelectElement, EmployeeFixture;

    protected function setUp(): void
    {
        $this->browser()->visit('/logout');
        $this->createEmployeeFixtures();
        $this->createLocationFixtures();
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
        $uri = "/admin/city/state/{$this->state->getId()}/create";

        $visit = $this->browser()->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri);

        $domDocument = $visit->crawler()->getNode(0)?->parentNode;

        $option = $domDocument->createElement('option');
        $option->setAttribute('value', $this->state->getId());
        $selectElement = $visit->crawler()->filter('select')->getNode(0);
        $selectElement->appendChild($option);
        $x = $visit->crawler()->filter('select')->getNode(0);

        $visit
            ->use(function (Browser $browser) {
                $this->addOption($browser, 'select', $this->state->getId());
            })
            ->fillField(
                'city_create_form[code]', 'DL'
            )->fillField(
                'city_create_form[name]', 'New Delhi'
            )
            ->fillField('city_create_form[state]', $this->state->getId())
            ->click('Save')
            ->assertSuccessful();

        $created = CityFactory::find(array('code' => 'DL'));

        $this->assertEquals("New Delhi", $created->getName());


    }

    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testDisplay()
    {


        $id = $this->city->getId();
        $uri = "/admin/city/$id/display";

        $this->browser()->visit($uri)->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)->assertSuccessful();


    }


    public function testList()
    {
        $uri = "/admin/city/state/{$this->state->getId()}/list";
        $this->browser()->visit($uri)->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)->assertSuccessful();

    }

}
