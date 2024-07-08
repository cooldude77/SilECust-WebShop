<?php

namespace App\Tests\Fixtures;

use App\Entity\Employee;
use App\Entity\User;
use App\Factory\EmployeeFactory;
use App\Factory\UserFactory;
use Zenstruck\Foundry\Proxy;

trait SuperAdminFixture
{
    private User|Proxy $userForSuperAdmin;


    private string $firstNameOfSuperAdminInString = 'Erin';
    private string $lastNameOfSuperAdminInString = 'Fukuhara';

    private string $emailOfSuperAdminInString = 'emp@superAdmin.com';
    private string $passwordForSuperAdminInString = 'SuperAdminPassword';

    private Proxy|Employee $superAdmin;

    public function createSuperAdmin(): void
    {

        $this->userForSuperAdmin = UserFactory::createOne
        (
            ['login' => $this->emailOfSuperAdminInString,
             'password' => $this->passwordForSuperAdminInString,
             'roles' => ['ROLE_SUPER_ADMIN']
            ]
        );
        $this->superAdmin = EmployeeFactory::createOne([
            'firstName' => $this->firstNameOfSuperAdminInString,
            'lastName' => $this->lastNameOfSuperAdminInString,
            'email' => $this->emailOfSuperAdminInString,
            'user' => $this->userForSuperAdmin]);

    }
}