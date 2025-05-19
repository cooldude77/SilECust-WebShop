<?php

namespace Silecust\WebShop\Service\Security\User\Employee;

use Silecust\WebShop\Entity\Employee;
use Silecust\WebShop\Entity\User;
use Silecust\WebShop\Exception\Security\User\Employee\UserNotAssociatedWithAnEmployeeException;
use Silecust\WebShop\Exception\Security\User\UserNotLoggedInException;
use Silecust\WebShop\Repository\EmployeeRepository;
use Symfony\Bundle\SecurityBundle\Security;

class EmployeeFromUserFinder
{

    public function __construct(private readonly Security $security,
        private readonly EmployeeRepository $employeeRepository
    ) {
    }

    /**
     * @return Employee
     * @throws UserNotLoggedInException
     * @throws UserNotAssociatedWithAnEmployeeException
     */
    public function getLoggedInEmployee(): Employee
    {
        if ($this->security->getUser() == null) {
            throw new UserNotLoggedInException();
        }

        /** @var User $user */
        $user = $this->security->getUser();

        $employee = $this->employeeRepository->findOneBy(['user' => $user]);

        if ($employee == null) {
            throw  new UserNotAssociatedWithAnEmployeeException($user);
        }

        return $employee;
    }

    public function isLoggedInUserAnEmployee(): bool
    {
        if ($this->security->getUser() == null) {

            return false;
        }

        /** @var User $user */
        $user = $this->security->getUser();

        $employee = $this->employeeRepository->findOneBy(['user' => $user]);

        if ($employee == null) {

            return false;
        }
        return true;
    }

}