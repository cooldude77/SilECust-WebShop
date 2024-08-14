<?php

namespace App\Service\MasterData\Employee\Mapper;

use App\Entity\Category;
use App\Entity\Employee;
use App\Form\MasterData\Employee\DTO\EmployeeDTO;
use App\Repository\EmployeeRepository;
use App\Repository\SalutationRepository;
use App\Security\Mapper\UserDTOMapper;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\String\ByteString;

class EmployeeDTOMapper
{

    public function __construct(private readonly EmployeeRepository $employeeRepository,
        private readonly SalutationRepository $salutationRepository,
        private readonly UserDTOMapper $userMapper
    ) {
    }

    public function mapToEntityForCreate(EmployeeDTO $employeeDTO): Employee
    {
        // generate random password
        // The employee will need to reset it
        // The password should not be sent over in plain text in any email
        $employeeDTO->plainPassword = ByteString::fromRandom(12);

        $user = $this->userMapper->mapUserForEmployeeCreate($employeeDTO);

        $employee = $this->employeeRepository->create($user);

        $employee->setFirstName($employeeDTO->firstName);
        $employee->setMiddleName($employeeDTO->middleName);
        $employee->setLastName($employeeDTO->lastName);
        $employee->setGivenName($employeeDTO->givenName);
        $employee->setEmail($employeeDTO->email);
        $employee->setPhoneNumber($employeeDTO->phoneNumber);

        return $employee;
    }


    public function mapToEntityForEdit(EmployeeDTO $employeeDTO): Employee
    {

        $employee = $this->employeeRepository->find($employeeDTO->id);
        $salutation = $this->salutationRepository->find($employeeDTO->salutationId);

        $employee->setSalutation($salutation);

        $employee->setFirstName($employeeDTO->firstName);
        $employee->setMiddleName($employeeDTO->middleName);
        $employee->setLastName($employeeDTO->lastName);
        $employee->setGivenName($employeeDTO->givenName);

        return $employee;

    }
}