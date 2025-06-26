<?php

namespace Silecust\WebShop\Service\MasterData\Customer\Address;

use Silecust\WebShop\Entity\Customer;
use Silecust\WebShop\Exception\MasterData\Customer\Address\AddressTypeIncorrect;
use Silecust\WebShop\Repository\CustomerAddressRepository;

class CustomerAddressQuery
{

    public function __construct(
        private readonly CustomerAddressRepository $customerAddressRepository) {
    }

    public function getAddressInASingleLine(int $id): string
    {
        $customerAddress = $this->customerAddressRepository->find($id);
        return $customerAddress->getLine1() . "\n"
            . ($customerAddress->getLine2() != null ? $customerAddress->getLine2() . "\n" : "")
            . ($customerAddress->getLine3() != null ? $customerAddress->getLine3() . "\n" : "")
            . $customerAddress->getCode()->getCity()->getName() . "\n"
            . $customerAddress->getCode()->getCity()->getState()->getName() . "\n"
            . $customerAddress->getCode()->getCity()->getState()->getCountry()->getName() . "\n"
            . $customerAddress->getCode()->getCode() . "\n";


    }

    /**
     * @param Customer $customer
     * @param string $addressType
     * @return bool
     * @throws AddressTypeIncorrect
     */
    public function checkAddressExistsForAddressType(Customer $customer, string $addressType): bool
    {
        if ($addressType != 'shipping')
            if ($addressType != 'billing')
                throw  new AddressTypeIncorrect($addressType);

        return count($this->customerAddressRepository->findBy(['customer' => $customer, 'addressType' => $addressType])) > 0;

    }


}