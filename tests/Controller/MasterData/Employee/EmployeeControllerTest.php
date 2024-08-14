<?php

namespace App\Tests\Controller\MasterData\Employee;

use App\Factory\EmployeeFactory;
use App\Factory\SalutationFactory;
use App\Factory\UserFactory;
use App\Tests\Fixtures\EmployeeFixture;
use App\Tests\Fixtures\SuperAdminFixture;
use App\Tests\Utility\SelectElement;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;
use function Symfony\Component\String\b;

class EmployeeControllerTest extends WebTestCase
{

    use HasBrowser, SuperAdminFixture, EmployeeFixture, SelectElement;


    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testCreate()
    {
        $createUrl = '/employee/create';

        $salutation = SalutationFactory::createOne(['name' => 'Mr.',
            'description' => 'Mister...']);

        $this->browser()
            ->visit($createUrl)
            ->use(function (Browser $browser) use ($salutation) {
                $response = $browser->client()->getResponse();
                $this->addOption($browser, 'select[name="employee_create_form[salutation]"]', $salutation->getId());
            })
            ->fillField(
                'employee_create_form[firstName]', 'First Name'
            )->fillField(
                'employee_create_form[lastName]', 'Last Name'
            )
            ->fillField('employee_create_form[salutation]', $salutation->getId())
            ->fillField('employee_create_form[email]', 'x@y.com')
            ->fillField('employee_create_form[phoneNumber]', '+91999999999')
            // leaving it here as password is now generated randomly and is also not sent over to the employee
            //   ->fillField('employee_create_form[plainPassword]', '4534geget355$%^')
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

        $this->browser()
            ->visit($url)
            ->use(function (Browser $browser) use ($salutation) {
                $this->addOption($browser, 'select[name="employee_edit_form[salutation]"]', $salutation->getId());
            })
            ->fillField('employee_edit_form[salutation]', $salutation->getId())
            ->fillField(
                'employee_edit_form[firstName]', 'New First Name'
            )->fillField(
                'employee_edit_form[middleName]', 'New Middle Name')
            ->fillField(
                'employee_edit_form[lastName]', 'New Last Name'
            )
            ->fillField('employee_edit_form[email]', 'f@g.com')
            ->fillField('employee_edit_form[phoneNumber]', '+9188888888')
            ->click('Save')
            ->assertSuccessful();

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
