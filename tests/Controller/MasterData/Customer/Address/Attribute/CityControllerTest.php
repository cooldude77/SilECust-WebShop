<?php

namespace App\Tests\Controller\MasterData\Customer\Address\Attribute;

use App\Factory\CityFactory;
use App\Tests\Fixtures\LocationFixture;
use App\Tests\Utility\SelectElement;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;

class CityControllerTest extends WebTestCase
{

    use HasBrowser, LocationFixture,SelectElement;

    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testCreate()
    {
        $createUrl = '/city/create';

        $this->createLocationFixtures();

        $visit = $this->browser()->visit($createUrl);

       /* $domDocument = $visit->crawler()->getNode(0)?->parentNode;

        $option = $domDocument->createElement('option');
        $option->setAttribute('value', $this->state->getId());
        $selectElement = $visit->crawler()->filter('select')->getNode(0);
        $selectElement->appendChild($option);
        $x = $visit->crawler()->filter('select')->getNode(0);
*/
        $visit
            ->use(function (Browser $browser){
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

        $this->createLocationFixtures();

        $id = $this->city->getId();
        $url = "/city/$id/display";

        $this->browser()->visit($url)->assertSuccessful();


    }


    public function testList()
    {
        $this->createLocationFixtures();
        $url = '/city/list';
        $this->browser()->visit($url)->assertSuccessful();

    }

}
