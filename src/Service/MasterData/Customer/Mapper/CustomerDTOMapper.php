<?php

namespace Silecust\WebShop\Service\MasterData\Customer\Mapper;

use Silecust\WebShop\Entity\Category;
use Silecust\WebShop\Entity\Customer;
use Silecust\WebShop\Form\MasterData\Customer\DTO\CustomerDTO;
use Silecust\WebShop\Repository\CustomerRepository;
use Silecust\WebShop\Repository\SalutationRepository;
use Silecust\WebShop\Security\Mapper\UserDTOMapper;
use Symfony\Component\Form\FormInterface;

class CustomerDTOMapper
{

    public function __construct(private readonly CustomerRepository $customerRepository,
        private readonly SalutationRepository $salutationRepository,
        private readonly UserDTOMapper $userMapper
    ) {
    }

    public function mapToEntityForCreate(CustomerDTO $customerDTO): Customer
    {
        $user = $this->userMapper->mapUserForCustomerCreate($customerDTO);

        $customer = $this->customerRepository->create($user);

        $customer->setFirstName($customerDTO->firstName);
        $customer->setMiddleName($customerDTO->middleName);
        $customer->setLastName($customerDTO->lastName);
        $customer->setGivenName($customerDTO->givenName);
        $customer->setEmail($customerDTO->email);
        $customer->setPhoneNumber($customerDTO->phoneNumber);

        return $customer;
    }


    public function mapToEntityForEdit(FormInterface $form, Customer $customer): Customer
    {

        $customerDTO = $form->getData();

        $customer->setFirstName($customerDTO->firstName);
        $customer->setMiddleName($customerDTO->middleName);
        $customer->setLastName($customerDTO->lastName);
        $customer->setGivenName($customerDTO->givenName);

        return $customer;

    }


    public function mapToDTOForEdit(Customer $customer): CustomerDTO
    {
        $customerDTO = new CustomerDTO();

        $customerDTO->firstName = $customer->getFirstName();
        $customerDTO->middleName = $customer->getMiddleName();
        $customerDTO->lastName = $customer->getLastName();
        $customerDTO->givenName = $customer->getGivenName();
        $customerDTO->email = $customer->getEmail();
        $customerDTO->phoneNumber = $customer->getPhoneNumber();

        return $customerDTO;

    }


}