<?php

namespace Silecust\WebShop\Service\MasterData\Customer\Address;

use Silecust\WebShop\Entity\Customer;
use Silecust\WebShop\Entity\CustomerAddress;
use Silecust\WebShop\Form\MasterData\Customer\Address\DTO\CustomerAddressDTO;
use Silecust\WebShop\Repository\CustomerAddressRepository;
use Silecust\WebShop\Repository\CustomerRepository;
use Silecust\WebShop\Repository\PostalCodeRepository;

readonly class CustomerAddressDTOMapper
{

    public function __construct(private CustomerRepository $customerRepository,
        private CustomerAddressRepository $customerAddressRepository,
        private PostalCodeRepository $postalCodeRepository,
    ) {
    }

    public function mapDtoToEntityForCreate(CustomerAddressDTO $customerAddressDTO): CustomerAddress
    {
        /** @var Customer $customer */
        $customer = $this->customerRepository->findOneBy(
            ['id' => $customerAddressDTO->customerId]
        );

        $customerAddress = $this->customerAddressRepository->create($customer);

        $customerAddress->setLine1($customerAddressDTO->line1);
        $customerAddress->setLine2($customerAddressDTO->line2);
        $customerAddress->setLine3($customerAddressDTO->line3);

        $customerAddress->setCustomer($customer);

        $customerAddress->setAddressType($customerAddressDTO->addressType);

        $customerAddress->setPostalCode(
            $this->postalCodeRepository->find(
                $customerAddressDTO->postalCodeId
            )
        );

        $customerAddress->setDefault($customerAddressDTO->isDefault);

        return $customerAddress;

    }

    public function mapDtoToEntityForUpdate(CustomerAddressDTO $customerAddressDTO
    ): CustomerAddress {


        /** @var CustomerAddress $customerAddress */
        $customerAddress = $this->customerAddressRepository->find(['id' => $customerAddressDTO->id]);

        $customerAddress->setLine1($customerAddressDTO->line1);

        $customerAddress->setLine2($customerAddressDTO->line2);

        $customerAddress->setLine3($customerAddressDTO->line3);

        $customerAddress->setAddressType($customerAddressDTO->addressType);

        $customerAddress->setPostalCode(
            $this->postalCodeRepository->find(
                $customerAddressDTO->postalCodeId
            )
        );

        $customerAddress->setDefault($customerAddressDTO->isDefault);

        return $customerAddress;
    }

    public function mapEntityToDtoForUpdate(int $id
    ): CustomerAddressDTO
    {

        $customerAddressDTO = new CustomerAddressDTO();

        /** @var CustomerAddress $customerAddress */
        $customerAddress = $this->customerAddressRepository->find($id);

        $customerAddressDTO->id = $customerAddress->getId();

        $customerAddressDTO->line1 = $customerAddress->getLine1();

        $customerAddressDTO->line2 = $customerAddress->getLine2();

        $customerAddressDTO->line3 = $customerAddress->getLine3();

        $customerAddressDTO->addressType = $customerAddress->getAddressType();

        $customerAddressDTO->postalCodeId =
            $this->postalCodeRepository->find(
                $customerAddress->getCode()->getId()
            )->getId();

        $customerAddressDTO->isDefault = $customerAddress->isDefault();

        return $customerAddressDTO;
    }

}