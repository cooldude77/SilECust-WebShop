<?php

namespace App\Service\MasterData\Customer\Address;

use App\Entity\Customer;
use App\Entity\CustomerAddress;
use App\Form\MasterData\Customer\Address\DTO\CustomerAddressDTO;
use App\Repository\CustomerAddressRepository;
use App\Repository\CustomerRepository;
use App\Repository\PinCodeRepository;

readonly class CustomerAddressDTOMapper
{

    public function __construct(private CustomerRepository $customerRepository,
        private CustomerAddressRepository $customerAddressRepository,
        private PinCodeRepository $pinCodeRepository,
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

        $customerAddress->setPinCode(
            $this->pinCodeRepository->find(
                $customerAddressDTO->pinCodeId
            )
        );

        $customerAddress->setDefault($customerAddressDTO->isDefault);

        return $customerAddress;

    }

    public function mapDtoToEntityForUpdate(CustomerAddressDTO $customerAddressDTO,
        CustomerAddress $customerAddress
    ): CustomerAddress {

        $customerAddress->setLine1($customerAddressDTO->line1);

        $customerAddress->setLine2($customerAddressDTO->line2);

        $customerAddress->setLine3($customerAddressDTO->line3);

        $customerAddress->setAddressType($customerAddressDTO->addressType);

        $customerAddress->setPinCode(
            $this->pinCodeRepository->find(
                $customerAddressDTO->pinCodeId
            )
        );

        $customerAddress->setDefault($customerAddressDTO->isDefault);

        return $customerAddress;
    }

}