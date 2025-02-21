<?php

namespace Silecust\WebShop\Tests\Fixtures;

use Silecust\WebShop\Entity\Employee;
use Silecust\WebShop\Entity\User;
use Silecust\WebShop\Factory\EmployeeFactory;
use Silecust\WebShop\Factory\UserFactory;
use Zenstruck\Foundry\Proxy;

trait EmployeeFixture
{
    private User|Proxy $userForEmployee;


    private string $firstNameOfEmployeeInString = 'Erin';
    private string $lastNameOfEmployeeInString = 'Fukuhara';

    private string $emailOfEmployeeInString = 'emp@employee.com';
    private string $passwordForEmployeeInString = 'EmployeePassword';

    private Proxy|Employee $employee;

    public function createEmployeeFixtures(): void
    {

        $this->userForEmployee = UserFactory::createOne
        (
            ['login' => $this->emailOfEmployeeInString,
             'password' => $this->passwordForEmployeeInString,
             'roles' => ['ROLE_EMPLOYEE']
            ]
        );
        $this->employee = EmployeeFactory::createOne([
            'firstName' => $this->firstNameOfEmployeeInString,
            'lastName' => $this->lastNameOfEmployeeInString,
            'email' => $this->emailOfEmployeeInString,
            'user' => $this->userForEmployee]);

    }
}