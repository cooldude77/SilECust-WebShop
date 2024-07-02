<?php

namespace App\Service\MasterData\Customer\Mapper;

use App\Entity\Category;
use App\Entity\Customer;
use App\Form\MasterData\Customer\DTO\CustomerDTO;
use App\Repository\CustomerRepository;
use App\Repository\SalutationRepository;
use App\Security\Mapper\UserDTOMapper;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CustomerDTOMapper
{

    public function __construct(private CustomerRepository $customerRepository,
        private SalutationRepository $salutationRepository,
        private UserDTOMapper $userMapper
    ) {

    }

    public function mapToEntityForCreate(FormInterface $form): Customer
    {
        /** @var Category $category */
        $salutation = $this->salutationRepository->find($form->get('salutationId')->getData());
        $customer = $this->customerRepository->create($salutation);

        /** @var CustomerDTO $customerDTO */
        $customerDTO = $form->getData();

        $customer->setFirstName($customerDTO->firstName);
        $customer->setMiddleName($customerDTO->middleName);
        $customer->setLastName($customerDTO->lastName);
        $customer->setGivenName($customerDTO->givenName);
        $customer->setEmail($customerDTO->email);
        $customer->setPhoneNumber($customerDTO->phoneNumber);

        $customer->setUser($this->userMapper->map($customerDTO, $customer));

        return $customer;
    }


    public function mapToEntityForEdit(FormInterface $form, Customer $customer): Customer
    {
        /** @var Category $category */
        $salutation = $this->salutationRepository->find($form->get('salutationId')->getData());

        $customerDTO = $form->getData();

        $customer->setSalutation($salutation);

        $customer->setFirstName($customerDTO->firstName);
        $customer->setMiddleName($customerDTO->middleName);
        $customer->setLastName($customerDTO->lastName);
        $customer->setGivenName($customerDTO->givenName);

        return $customer;

    }

  
}