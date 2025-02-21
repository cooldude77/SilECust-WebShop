<?php

namespace Silecust\WebShop\Tests\Fixtures;

use Silecust\WebShop\Entity\Employee;
use Silecust\WebShop\Entity\User;
use Silecust\WebShop\Factory\EmployeeFactory;
use Silecust\WebShop\Factory\UserFactory;
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