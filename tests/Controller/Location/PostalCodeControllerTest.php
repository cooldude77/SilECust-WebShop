<?php

namespace Silecust\WebShop\Tests\Controller\Location;

use Silecust\WebShop\Factory\CityFactory;
use Silecust\WebShop\Factory\CountryFactory;
use Silecust\WebShop\Factory\PostalCodeFactory;
use Silecust\WebShop\Factory\StateFactory;
use Silecust\WebShop\Service\Testing\Fixtures\EmployeeFixture;
use Silecust\WebShop\Service\Testing\Fixtures\LocationFixture;
use Silecust\WebShop\Service\Testing\Utility\SelectElement;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;

class PostalCodeControllerTest extends WebTestCase
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
        $city = CityFactory::createOne(['code' => 'BLR', 'name' => 'Bangalore', 'state' => $state]);

        $uri = "/admin/postal_code/city/{$city->getId()}/create";

        $visit = $this->browser()->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)
            ->use(function (Browser $browser) use ($city) {
                $this->addOption($browser, 'select', $city->getId());
            })
            ->fillField(
                'postal_code_create_form[postalCode]', '560001'
            )->fillField(
                'postal_code_create_form[name]', 'M G Road'
            )
            ->click('Save')
            ->assertSuccessful();

        $created = PostalCodeFactory::find(array('code' => '560001'));

        $this->assertEquals("M G Road", $created->getName());


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
        $postalCode = PostalCodeFactory::createOne(['code' => '560001', 'name' => 'M G Road', 'city' => $city]);


        $uri = "/admin/postal_code/{$postalCode->getId()}/display";

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
        $postalCode = PostalCodeFactory::createOne(['code' => '560001', 'name' => 'M G Road', 'city' => $city]);

        $uri = "/admin/postal_code/{$postalCode->getId()}/edit";

        $this->browser()->visit($uri)->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)
            ->fillField(
                'postal_code_edit_form[name]', 'MG Road Main'
            )
            ->click('Save')
            ->assertSuccessful();

        $postalCode = PostalCodeFactory::find(array('code' => '560001'));

        $this->assertEquals("MG Road Main", $postalCode->getName());


    }

    public function testList()
    {
        $country = CountryFactory::createOne(['code' => 'IN', 'name' => 'India']);
        $state1 = StateFactory::createOne(['code' => 'KA', 'name' => 'Karnataka', 'country' => $country]);
        $state2 = StateFactory::createOne(['code' => 'RJ', 'name' => 'Rajasthan', 'country' => $country]);
        $city1 = CityFactory::createOne(['code' => 'BLR', 'name' => 'Bangalore', 'state' => $state1]);
        $city2 = CityFactory::createOne(['code' => 'JPR', 'name' => 'Jaipur', 'state' => $state2]);

        $postalCode1 = PostalCodeFactory::createOne(['code' => '560001', 'name' => 'M G Road', 'city' => $city1]);
        $postalCode2 = PostalCodeFactory::createOne(['code' => '302001', 'name' => 'Main Road', 'city' => $city2]);

        $uri = "/admin/postal_code/city/{$city1->getId()}/list";

        $this->browser()->visit($uri)->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)->assertSuccessful();

        $uri = "/admin/postal_code/city/{$city2->getId()}/list";

        $this->browser()
            ->visit($uri)
            ->visit($uri)
            ->assertSuccessful();

    }
}
