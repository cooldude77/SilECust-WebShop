<?php

namespace Silecust\WebShop\Tests\Controller\Finance\Currency;

use Silecust\WebShop\Factory\CountryFactory;
use Silecust\WebShop\Factory\CurrencyFactory;
use Silecust\WebShop\Service\Testing\Fixtures\EmployeeFixture;
use Silecust\WebShop\Service\Testing\Fixtures\LocationFixture;
use Silecust\WebShop\Service\Testing\Utility\SelectElement;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;

class CurrencyControllerTest extends WebTestCase
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

    public function testDisplay()
    {

        $currency = CurrencyFactory::createOne(['code' => 'IN', 'description' => 'Indian Rupees']);

        $uri = "/admin/currency/{$currency->getId()}/display";

        $this->browser()->visit($uri)->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)
            ->assertSuccessful();


    }

    public function testList()
    {
        $uri = "/admin/currency/list";
        $this->browser()->visit($uri)->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)->assertSuccessful();

    }

    public function testEdit()
    {
        $currency = CurrencyFactory::createOne(['code' => 'IN', 'description' => 'Indian Rupees']);

        $uri = "/admin/currency/{$currency->getId()}/edit";

        $this->browser()->visit($uri)->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)
            ->fillField(
                'currency_edit_form[description]', 'India-Currency'
            )
            ->click('Save')
            ->assertSuccessful();

        $created = CurrencyFactory::find(array('code' => 'IN'));

        $this->assertEquals("India-Currency", $created->getDescription());


    }

    public function testCreate()
    {

        $country = CountryFactory::createOne(['code' => 'IN', 'name' => 'India']);

        $uri = "/admin/currency/create";

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
                'currency_create_form[code]', 'IN'
            )->fillField(
                'currency_create_form[description]', 'Indian Rupees'
            )
            ->fillField('currency_create_form[country]', $country->getId())
            ->fillField('currency_create_form[symbol]', '₹')
            ->click('Save')
            ->assertSuccessful();

        $created = CurrencyFactory::find(array('code' => 'IN'));

        $this->assertEquals("Indian Rupees", $created->getDescription());

    }
}
