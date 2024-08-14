<?php

namespace App\Service\Module\WebShop\External\Address;

use App\Repository\CustomerAddressRepository;
use App\Service\Security\User\Customer\CustomerFromUserFinder;
use Symfony\Component\HttpFoundation\RequestStack;

class CheckOutAddressQuery
{
    public const BILLING_ADDRESS_ID = "BILLING_ADDRESS_SET_ID";
    public const SHIPPING_ADDRESS_ID = "SHIPPING_ADDRESS_SET_ID";

    public function __construct(
        private readonly CustomerAddressRepository $customerAddressRepository,
        private readonly CheckOutAddressSession $checkOutAddressSession,
        private readonly CustomerFromUserFinder $customerFromUserFinder,
        RequestStack $requestStack
    ) {

    }

    /**
     * @return bool
     * @throws \App\Exception\Security\User\Customer\UserNotAssociatedWithACustomerException
     * @throws \App\Exception\Security\User\UserNotLoggedInException
     */
    public function isAddressValid(int $id): bool
    {


        $addresses = $this->customerAddressRepository->findOneBy([
            'customer' => $this->customerFromUserFinder->getLoggedInCustomer(),
            'id'=>$id]);

         return $addresses != null;

    }


    public function setShippingAddress(int $id): void
    {

        // todo check address valid
        $this->checkOutAddressSession->setShippingAddress($id);
    }

    public function setBillingAddress(int $id): void
    {

        // todo check address valid
        $this->checkOutAddressSession->setBillingAddress($id);

    }


    public function isShippingAddressChosen(): bool
    {
        // todo check address valid

        return $this->checkOutAddressSession->isShippingAddressSet();
    }

    public function isBillingAddressChosen(): bool
    {

        // todo check address valid
        return $this->checkOutAddressSession->isBillingAddressSet();

    }

}