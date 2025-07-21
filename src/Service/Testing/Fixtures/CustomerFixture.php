<?php

namespace Silecust\WebShop\Service\Testing\Fixtures;

use Silecust\WebShop\Entity\Customer;
use Silecust\WebShop\Entity\User;
use Silecust\WebShop\Factory\CustomerFactory;
use Silecust\WebShop\Factory\UserFactory;
use Zenstruck\Foundry\Proxy;

trait CustomerFixture
{
    private User|Proxy $userForCustomerA;

    private string $loginForCustomerAInString = 'cust@customer.com';
    private string $passwordForCustomerAInString = 'CustomerPassword';
    private string $firstNameInStringForCustomerA = 'Jack';
    private string $lastNameInStringForCustomerA = 'Johnson';

    private string $customerEmailInStringForCustomerA = 'cust@customer.com';

    private Proxy|Customer $customerA;

    public function createCustomerFixtures(): void
    {

        $this->userForCustomerA = UserFactory::createOne
        (
            ['login' => $this->loginForCustomerAInString,
             'password' => $this->passwordForCustomerAInString,
             'roles' => ['ROLE_CUSTOMER']
            ]
        );
        $this->customerA = CustomerFactory::createOne([
            'firstName' => $this->firstNameInStringForCustomerA,
            'lastName' => $this->lastNameInStringForCustomerA,
            'email' => $this->customerEmailInStringForCustomerA,
            'user' => $this->userForCustomerA]);

    }
}