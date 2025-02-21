<?php

namespace Silecust\WebShop\Service\Module\WebShop\External\Address;

use Silecust\WebShop\Entity\CustomerAddress;
use Silecust\WebShop\Exception\Module\WebShop\External\Address\NoAddressChosenAtCheckout;
use Silecust\WebShop\Form\Module\WebShop\External\Address\Existing\DTO\AddressChooseExistingMultipleDTO;
use Silecust\WebShop\Form\Module\WebShop\External\Address\Existing\DTO\AddressChooseExistingSingleDTO;
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
    public function setAddressInSession(AddressChooseExistingMultipleDTO $multipleDTO,
        string $addressType
    ): ?CustomerAddress {
        /** @var AddressChooseExistingSingleDTO $address */
        foreach ($multipleDTO->addresses as $address) {
            if ($address->isChosen) {
                if ($addressType == 'shipping') {
                    $this->checkOutAddressSession->setShippingAddress($address->id);
                    return $this->customerAddressRepository->find($address->id);
                } elseif ($addressType == 'billing') {
                    $this->checkOutAddressSession->setBillingAddress($address->id);
                    return $this->customerAddressRepository->find($address->id);

                }
            }
        }

        throw new NoAddressChosenAtCheckout();


    }
}