<?php

namespace App\Security\Mapper;

use App\Entity\User;
use App\Form\MasterData\Customer\DTO\CustomerDTO;
use App\Form\MasterData\Employee\DTO\EmployeeDTO;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 *
 */
class UserDTOMapper
{


    /**
     * @param UserPasswordHasherInterface $userPasswordHasher
     */
    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher
    ) {
    }


    /**
     * @param EmployeeDTO $employeeDTO
     *
     * @return User
     */
    public function mapUserForEmployeeCreate(EmployeeDTO $employeeDTO): User
    {
        $user = new User();
        $user->setLogin($employeeDTO->email);

        // encode the plain password
        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                $employeeDTO->plainPassword
            )
        );

        $user->setRoles(['ROLE_ADMIN']);
        return $user;
    }

    /**
     * @param CustomerDTO $customerDTO
     *
     * @return User
     */
    public function mapUserForCustomerCreate(CustomerDTO $customerDTO): User
    {
        $user = new User();
        $user->setLogin($customerDTO->email);

        // encode the plain password
        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                $customerDTO->plainPassword
            )
        );

        $user->setRoles(['ROLE_CUSTOMER']);
        return $user;
    }
}