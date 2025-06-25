<?php

namespace Silecust\WebShop\Service\Module\WebShop\External\Address;

use Silecust\WebShop\Entity\CustomerAddress;
use Silecust\WebShop\Exception\Module\WebShop\External\Address\NoAddressChosenAtCheckout;
use Silecust\WebShop\Form\Module\WebShop\External\Address\Existing\DTO\AddressChooseExistingMultipleDTO;
use Silecust\WebShop\Repository\CustomerAddressRepository;

readonly class CheckoutAddressChooseParser
{
    public function __construct(
        private readonly CheckOutAddressSession $checkOutAddressSession,
        private readonly CustomerAddressRepository $customerAddressRepository
    ) {
    }

    /**
     * @param AddressChooseExistingMultipleDTO $multipleDTO
     * @param string                           $addressType
     *
     * @return CustomerAddress|null
     * @throws NoAddressChosenAtCheckout
     */
    public function setAddressInSession(int $addressId, string $addressType): ?CustomerAddress
    {

        $address = $this->customerAddressRepository->find($addressId);

        if ($address->getAddressType() == CustomerAddress::ADDRESS_TYPE_SHIPPING)
            $this->checkOutAddressSession->setShippingAddress($addressId);
        if ($address->getAddressType() == CustomerAddress::ADDRESS_TYPE_BILLING)
            $this->checkOutAddressSession->setBillingAddress($addressId);

        return $address;

    }
}