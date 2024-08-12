<?php

namespace App\Service\Security\User\Customer;

use App\Entity\Customer;
use App\Entity\User;
use App\Exception\Security\User\Customer\UserNotAssociatedWithACustomerException;
use App\Exception\Security\User\UserNotLoggedInException;
use App\Repository\CustomerRepository;
use Symfony\Bundle\SecurityBundle\Security;

readonly class CustomerFromUserFinder
{

    public function __construct(private Security $security,
        private CustomerRepository $customerRepository
    ) {
    }

    /**
     * @return Customer
     * @throws UserNotAssociatedWithACustomerException
     * @throws UserNotLoggedInException
     */
    public function getLoggedInCustomer(): Customer
    {
        if ($this->security->getUser() == null) {
            throw new UserNotLoggedInException();
        }

        /** @var User $user */
        $user = $this->security->getUser();

        $customer = $this->customerRepository->findOneBy(['user' => $user]);

        if ($customer == null) {
            throw  new UserNotAssociatedWithACustomerException($user);
        }

        return $customer;
    }

    public function isLoggedInUserAlsoACustomer(): bool
    {
        if ($this->security->getUser() == null) {

            return false;
        }

        /** @var User $user */
        $user = $this->security->getUser();

        $customer = $this->customerRepository->findOneBy(['user' => $user]);

        if ($customer == null) {

            return false;
        }
        return true;
    }

}