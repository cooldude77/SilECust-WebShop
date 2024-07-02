<?php

namespace App\Service\Security\User\Customer;

use App\Entity\Customer;
use App\Entity\User;
use App\Repository\CustomerRepository;
use App\Service\Component\Database\DatabaseOperations;

readonly class CustomerService
{
    public function __construct(private readonly DatabaseOperations $databaseOperations,
        private readonly CustomerRepository $customerRepository
    ) {
    }


    public function save(Customer $customer):void
    {
        $this->databaseOperations->persist($customer);
        $this->databaseOperations->flush();

    }

    public function mapCustomerFromSimpleSignUp(User $user): Customer
    {

        $customer = $this->customerRepository->create($user);

        if (filter_var($user->getLogin(), FILTER_VALIDATE_EMAIL)) {
            $customer->setEmail($user->getLogin());
        } else {
            $customer->setPhoneNumber($user->getLogin());
        }

        return $customer;
    }

}