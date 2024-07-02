<?php

namespace App\Security\Mapper;

use App\Entity\Customer;
use App\Entity\Employee;
use App\Entity\User;
use App\Form\MasterData\Customer\DTO\CustomerDTO;
use App\Form\MasterData\Employee\DTO\EmployeeDTO;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserDTOMapper
{


    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher)
    {
    }


    public function mapUserForEmployeeCreate(EmployeeDTO $employeeDTO, Employee $employee):User
    {
        $user = new User();
        $user->setLogin($employee->getEmail());

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
}