<?php

namespace App\Service\MasterData\Employee\Mapper;

use App\Entity\Category;
use App\Entity\Employee;
use App\Form\MasterData\Employee\DTO\EmployeeDTO;
use App\Repository\EmployeeRepository;
use App\Repository\SalutationRepository;
use App\Security\Mapper\UserDTOMapper;
use Symfony\Component\Form\FormInterface;

class EmployeeDTOMapper
{

    public function __construct(private readonly EmployeeRepository $employeeRepository,
        private readonly SalutationRepository $salutationRepository,
        private readonly UserDTOMapper $userMapper
    ) {
    }

    public function mapToEntityForCreate(EmployeeDTO $employeeDTO): Employee
    {
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


    public function mapToEntityForEdit(FormInterface $form, Employee $employee): Employee
    {
        /** @var Category $category */
        $salutation = $this->salutationRepository->find($form->get('salutationId')->getData());

        $employeeDTO = $form->getData();

        $employee->setSalutation($salutation);

        $employee->setFirstName($employeeDTO->firstName);
        $employee->setMiddleName($employeeDTO->middleName);
        $employee->setLastName($employeeDTO->lastName);
        $employee->setGivenName($employeeDTO->givenName);

        return $employee;

    }
}