<?php

namespace Silecust\WebShop\Service\Module\WebShop\External\Address;

use Silecust\WebShop\Entity\CustomerAddress;
use Silecust\WebShop\Service\MasterData\Customer\Address\CustomerAddressSave;

readonly class CheckOutAddressSave
{

    public function __construct(
        private readonly CustomerAddressSave $customerAddressSave,
        private readonly CheckOutAddressSession $checkOutAddressSession
    ) {

    }


    public function save(CustomerAddress $customerAddress, bool $isChosen): CustomerAddress
    {


        $customerAddress = $this->customerAddressSave->save($customerAddress);
        if ($isChosen) {
            if ($customerAddress->getAddressType() == 'shipping') {
                $this->checkOutAddressSession->setShippingAddress($customerAddress->getId());
            } elseif ($customerAddress->getAddressType() == 'billing') {
                $this->checkOutAddressSession->setBillingAddress($customerAddress->getId());
            }
        }

        return $customerAddress;

    }

}