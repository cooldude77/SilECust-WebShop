<?php

namespace App\Tests\Controller\MasterData\Customer;

use App\Factory\CustomerFactory;
use App\Factory\SalutationFactory;
use App\Tests\Fixtures\EmployeeFixture;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser\Test\HasBrowser;

class CustomerControllerTest extends WebTestCase
{

    use HasBrowser,EmployeeFixture;

    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testCreate()
    {
        $createUrl = '/customer/create';

        $salutation = SalutationFactory::createOne(['name' => 'Mr.',
                                                    'description' => 'Mister...']);

        $visit = $this->browser()->visit($createUrl);

        $crawler = $visit->client()->getCrawler();

        $domDocument = $crawler->getNode(0)?->parentNode;

        $option = $domDocument->createElement('option');
        $option->setAttribute('value', $salutation->getId());
        $selectElement = $crawler->filter('select')->getNode(0);
        $selectElement->appendChild($option);

        $visit->fillField(
            'customer_create_form[firstName]', 'First Name'
        )->fillField('customer_create_form[lastName]', 'Last Name'
        )->fillField('customer_create_form[salutationId]', $salutation->getId())
            ->fillField('customer_create_form[email]', 'x@y.com')
            ->fillField('customer_create_form[phoneNumber]', '+91999999999')
            ->fillField('customer_create_form[plainPassword]', '4534geget355$%^')
            ->click('Save')
            ->assertSuccessful();

        $created = CustomerFactory::find(array('firstName' => 'First Name'));

        $this->assertEquals("First Name", $created->getFirstName());


    }

    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testEdit()
    {

        $salutation = SalutationFactory::createOne(['name' => 'Mr.',
                                                    'description' => 'Mister...']);

        $this->createCustomer();

        $id = $this->customer->getId();

        $url = "/customer/$id/edit";

        $visit = $this->browser()->visit($url);

        $crawler = $visit->client()->getCrawler();

        $domDocument = $crawler->getNode(0)?->parentNode;

        $option = $domDocument->createElement('option');
        $option->setAttribute('value', $salutation->getId());
        $selectElement = $crawler->filter('select')->getNode(0);
        $selectElement->appendChild($option);

        $visit->fillField(
            'customer_edit_form[firstName]', 'New First Name'
        )->fillField(
            'customer_edit_form[middleName]', 'New Middle Name'
        )
            ->fillField(
                'customer_edit_form[lastName]', 'New Last Name'
            )

            ->fillField('customer_edit_form[email]', 'f@g.com')
            ->fillField('customer_edit_form[phoneNumber]', '+9188888888')
            ->click('Save')->assertSuccessful();

        $created = CustomerFactory::find(array('firstName' => "New First Name"));

        $this->assertEquals("New First Name", $created->getFirstName());


    }

    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testDisplay()
    {

        $this->createCustomer();

        $id = $this->customer->getId();
        $url = "/customer/$id/display";

        $this->browser()->visit($url)->assertSuccessful();


    }


    public function testList()
    {

        $url = '/customer/list';
        $this->browser()->visit($url)->assertSuccessful();

    }


}
