<?php

namespace App\Tests\Fixtures;

use App\Entity\Employee;
use App\Entity\User;
use App\Factory\EmployeeFactory;
use App\Factory\UserFactory;
use Zenstruck\Foundry\Proxy;

trait EmployeeFixture
{
    private User|Proxy $userForEmployee;

    private string $loginForEmployeeInString = 'emp@employee.com';
    private string $passwordForEmployeeInString = 'EmployeePassword';

    private Proxy|Employee $employee;

    public function createEmployee(): void
    {

        $this->userForEmployee = UserFactory::createOne
        (
            ['login' => $this->loginForEmployeeInString,
             'password' => $this->passwordForEmployeeInString]
        );
        $this->employee = EmployeeFactory::createOne(['user' => $this->userForEmployee]);

    }
}