<?php

namespace App\Tests\Controller\MasterData\Customer\Address;

use App\Factory\CustomerAddressFactory;
use App\Tests\Fixtures\CustomerFixture;
use App\Tests\Fixtures\LocationFixture;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser\Test\HasBrowser;

class CustomerAddressControllerTest extends WebTestCase
{

    use HasBrowser;
    use LocationFixture;
    use CustomerFixture;

    public function testCreate()
    {

        $this->createLocationFixtures();

        $this->createCustomer();

        $id = $this->customer->getId();

        $uri = "/customer/{$id}/address/create";
        $this->browser()
            ->visit($uri)
            ->fillField(
                'customer_address_create_form[line1]', 'Line 1'
            )
            ->fillField(
                'customer_address_create_form[line2]', 'Line 2'
            )
            ->fillField(
                'customer_address_create_form[line3]', 'Line 3'
            )
            ->fillField(
                'customer_address_create_form[addressType]', 'billing'
            )
            ->click('Save')
            ->assertSuccessful();

        $created = CustomerAddressFactory::find(array('line1' => 'Line 1'));

        $this->assertEquals('Line 1', $created->getLine1());
        $this->assertEquals('Line 2', $created->getLine2());
        $this->assertEquals('Line 3', $created->getLine3());
        $this->assertEquals('billing', $created->getAddressType());

    }


    public function testEdit()
    {

        $this->createLocationFixtures();

        $this->createCustomer();

        $customerAddress = CustomerAddressFactory::createOne(['customer' => $this->customer,
            'addressType'=>'shipping']);

        $id = $customerAddress->getId();

        $uri = "/customer/address/{$id}/edit";
        $this->browser()
            ->visit($uri)
            ->fillField(
                'customer_address_edit_form[line1]', 'Line 1'
            )
            ->fillField(
                'customer_address_edit_form[line2]', 'Line 2'
            )
            ->fillField(
                'customer_address_edit_form[line3]', 'Line 3'
            )
            ->fillField(
                'customer_address_edit_form[addressType]', 'billing'
            )
            ->click('Save')
            ->assertSuccessful();

        $created = CustomerAddressFactory::find(array('line1' => 'Line 1'));

        $this->assertEquals('Line 1', $created->getLine1());
        $this->assertEquals('Line 2', $created->getLine2());
        $this->assertEquals('Line 3', $created->getLine3());
        $this->assertEquals('billing', $created->getAddressType());
    }

    public function testList()
    {
        $this->createCustomer();

        CustomerAddressFactory::createMany(10, ['customer' => $this->customer,
            'addressType'=>'shipping']);

        $id = $this->customer->getId();
        $url = "/customer/{$id}/address/list";
        $this->browser()->visit($url)->assertSuccessful();

    }

}
