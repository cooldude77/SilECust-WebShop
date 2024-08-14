<?php

namespace App\Service\Module\WebShop\External\Address;

use App\Entity\CustomerAddress;
use App\Repository\CustomerAddressRepository;
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
        $this->requestStack->getSession()->set(self::BILLING_ADDRESS_ID, $id);

    }

    public function setShippingAddress(int $id): void
    {
        $this->requestStack->getSession()->set(self::SHIPPING_ADDRESS_ID, $id);

    }

    public function isShippingAddressSet(): bool
    {
        // todo: verify id
        return $this->requestStack->getSession()->get(self::SHIPPING_ADDRESS_ID) != null;

    }

    public function isBillingAddressSet(): bool
    {

        // todo: verify id
        return $this->requestStack->getSession()->get(self::BILLING_ADDRESS_ID) != null;


    }


    private function setChosen(bool $isChosen,$type):void
    {

        $key = $type == "billing" ? self::BILLING_ADDRESS_ID : self::SHIPPING_ADDRESS_ID;
        $this->requestStack->getSession()->set($key,$isChosen);
    }


    public function getBillingAddress():CustomerAddress
    {
        return $this->customerAddressRepository->find(
            $this->requestStack->getSession()->get(self::BILLING_ADDRESS_ID)
        );
    }

    public function getShippingAddress():CustomerAddress
    {
        return $this->customerAddressRepository->find(
            $this->requestStack->getSession()->get(self::SHIPPING_ADDRESS_ID)
        );
    }

}