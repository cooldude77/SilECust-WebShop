<?php

namespace Silecust\WebShop\Service\Security\User\Customer;

use Silecust\WebShop\Entity\Customer;
use Silecust\WebShop\Entity\User;
use Silecust\WebShop\Exception\Security\User\Customer\UserNotAssociatedWithACustomerException;
use Silecust\WebShop\Exception\Security\User\UserNotLoggedInException;
use Silecust\WebShop\Repository\CustomerRepository;
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

    public function isLoggedInUserACustomer(): bool
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