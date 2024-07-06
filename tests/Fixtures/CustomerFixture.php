<?php

namespace App\Tests\Fixtures;

use App\Entity\Customer;
use App\Entity\User;
use App\Factory\CustomerFactory;
use App\Factory\UserFactory;
use Zenstruck\Foundry\Proxy;

trait CustomerFixture
{
    private User|Proxy $userForCustomer;

    private string $loginForCustomerInString = 'cust@customer.com';
    private string $passwordForCustomerInString = 'CustomerPassword';
    private string $firstNameInString = 'Jack';
    private string $lastNameInString = 'Johnson';

    private string $customerEmailInString = 'cust@customer.com';

    private Proxy|Customer $customer;

    public function createCustomer(): void
    {

        $this->userForCustomer = UserFactory::createOne
        (
            ['login' => $this->loginForCustomerInString,
             'password' => $this->passwordForCustomerInString,
             'roles' => ['ROLE_CUSTOMER']
            ]
        );
        $this->customer = CustomerFactory::createOne([
            'firstName' => $this->firstNameInString,
            'lastName' => $this->lastNameInString,
            'email' => $this->customerEmailInString,
            'user' => $this->userForCustomer]);

    }
}