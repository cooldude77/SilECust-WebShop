<?php

namespace Silecust\WebShop\Service\Testing\Fixtures;

use Silecust\WebShop\Entity\Customer;
use Silecust\WebShop\Entity\User;
use Silecust\WebShop\Factory\CustomerFactory;
use Silecust\WebShop\Factory\UserFactory;
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

    public function createCustomerFixtures(): void
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