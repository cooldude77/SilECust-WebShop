<?php

namespace Silecust\WebShop\Service\Testing\Fixtures;

use Silecust\WebShop\Entity\Customer;
use Silecust\WebShop\Entity\User;
use Silecust\WebShop\Factory\CustomerFactory;
use Silecust\WebShop\Factory\UserFactory;
use Zenstruck\Foundry\Proxy;

trait CustomerFixtureB
{
    private User|Proxy $userForCustomerB;

    private string $loginForCustomerBInString = 'custA@customer.com';
    private string $passwordForCustomerBInString = 'CustomerPassword';
    private string $firstNameInStringForCustomerB = 'Mary';
    private string $lastNameInStringForCustomerB = 'Frank';

    private string $customerEmailInStringForCustomerB = 'custA@customer.com';

    private Proxy|Customer $customerB;

    public function createCustomerFixturesB(): void
    {

        $this->userForCustomerB = UserFactory::createOne
        (
            ['login' => $this->loginForCustomerBInString,
             'password' => $this->passwordForCustomerBInString,
             'roles' => ['ROLE_CUSTOMER']
            ]
        );
        $this->customerB = CustomerFactory::createOne([
            'firstName' => $this->firstNameInStringForCustomerB,
            'lastName' => $this->lastNameInStringForCustomerB,
            'email' => $this->customerEmailInStringForCustomerB,
            'user' => $this->userForCustomerB]);

    }
}