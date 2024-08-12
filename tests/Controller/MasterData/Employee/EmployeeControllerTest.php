<?php

namespace App\Tests\Controller\MasterData\Employee;

use App\Factory\EmployeeFactory;
use App\Factory\SalutationFactory;
use App\Factory\UserFactory;
use App\Tests\Fixtures\EmployeeFixture;
use App\Tests\Fixtures\SuperAdminFixture;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;

class EmployeeControllerTest extends WebTestCase
{

    use HasBrowser, SuperAdminFixture, EmployeeFixture;


    public function testOnlySuperAdminCanCallCreateOrEditOrDisplayUrls(): void
    {

        $this->createSuperAdmin();
        $this->createEmployee();

        $adminUrl = '/admin';

        $this->browser()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($adminUrl)
            ->assertNotSee('aEmployees');

        $createUrl = '/employee/create';




    }

    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testCreate()
    {
        $createUrl = '/employee/create';

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
            'employee_create_form[firstName]', 'First Name'
        )->fillField(
            'employee_create_form[lastName]', 'Last Name'
        )->fillField('employee_create_form[salutationId]', $salutation->getId())
            ->fillField('employee_create_form[email]', 'x@y.com')
            ->fillField('employee_create_form[phoneNumber]', '+91999999999')
            ->fillField('employee_create_form[plainPassword]', '4534geget355$%^')
            ->click('Save')
            ->assertSuccessful();

        $created = EmployeeFactory::find(array('firstName' => 'First Name'));

        $this->assertEquals("First Name", $created->getFirstName());

        $user = $created->getUser();

        $this->assertTrue(in_array('ROLE_ADMIN', $created->getUser()->getRoles()));

    }

    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testEdit()
    {

        $salutation = SalutationFactory::createOne(['name' => 'Mr.',
                                                    'description' => 'Mister...']);

        $user = UserFactory::createOne();

        $employee = EmployeeFactory::createOne(['firstName' => "First Name", 'user' => $user]);

        $id = $employee->getId();

        $url = "/employee/$id/edit";

        $visit = $this->browser()->visit($url);

        $crawler = $visit->client()->getCrawler();

        $domDocument = $crawler->getNode(0)?->parentNode;

        $option = $domDocument->createElement('option');
        $option->setAttribute('value', $salutation->getId());
        $selectElement = $crawler->filter('select')->getNode(0);
        $selectElement->appendChild($option);

        $visit->fillField(
            'employee_edit_form[firstName]', 'New First Name'
        )->fillField(
            'employee_edit_form[middleName]', 'New Middle Name'
        )
            ->fillField(
                'employee_edit_form[lastName]', 'New Last Name'
            )
            ->fillField('employee_edit_form[email]', 'f@g.com')
            ->fillField('employee_edit_form[phoneNumber]', '+9188888888')
            ->click('Save')->assertSuccessful();

        $created = EmployeeFactory::find(array('firstName' => "New First Name"));

        $this->assertEquals("New First Name", $created->getFirstName());


    }

    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testDisplay()
    {

        $employee = EmployeeFactory::createOne();

        $id = $employee->getId();
        $url = "/employee/$id/display";

        $this->browser()->visit($url)->assertSuccessful();


    }


    public function testList()
    {

        $url = '/employee/list';
        $this->browser()->visit($url)->assertSuccessful();

    }


}
