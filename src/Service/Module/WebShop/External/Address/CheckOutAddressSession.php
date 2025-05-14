<?php

namespace Silecust\WebShop\Service\Module\WebShop\External\Address;

use Silecust\WebShop\Entity\CustomerAddress;
use Silecust\WebShop\Repository\CustomerAddressRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CheckOutAddressSession
{
    public const BILLING_ADDRESS_ID = "BILLING_ADDRESS_SET_ID";
    public const SHIPPING_ADDRESS_ID = "SHIPPING_ADDRESS_SET_ID";

    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly CustomerAddressRepository $customerAddressRepository
    ) {

    }



    public function setBillingAddress(int $id): void
    {
       $this->requestStack->getMainRequest()->getSession()->set(self::BILLING_ADDRESS_ID, $id);

    }

    public function setShippingAddress(int $id): void
    {
        $this->requestStack->getMainRequest()->getSession()->set(self::SHIPPING_ADDRESS_ID, $id);

    }

    public function isShippingAddressSet(): bool
    {
        // todo: verify id
        return $this->requestStack->getMainRequest()->getSession()->get(self::SHIPPING_ADDRESS_ID) != null;

    }

    public function isBillingAddressSet(): bool
    {

        // todo: verify id
        return $this->requestStack->getMainRequest()->getSession()->get(self::BILLING_ADDRESS_ID) != null;


    }


    private function setChosen(bool $isChosen,$type):void
    {

        $key = $type == "billing" ? self::BILLING_ADDRESS_ID : self::SHIPPING_ADDRESS_ID;
        $this->requestStack->getMainRequest()->getSession()->set($key,$isChosen);
    }


    public function getBillingAddress():CustomerAddress
    {
        return $this->customerAddressRepository->find(
            $this->requestStack->getMainRequest()->getSession()->get(self::BILLING_ADDRESS_ID)
        );
    }

    public function getShippingAddress():CustomerAddress
    {
        return $this->customerAddressRepository->find(
            $this->requestStack->getMainRequest()->getSession()->get(self::SHIPPING_ADDRESS_ID)
        );
    }

}