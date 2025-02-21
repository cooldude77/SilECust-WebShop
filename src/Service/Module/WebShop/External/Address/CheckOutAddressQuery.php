<?php

namespace Silecust\WebShop\Service\Module\WebShop\External\Address;

use Silecust\WebShop\Exception\Security\User\Customer\UserNotAssociatedWithACustomerException;
use Silecust\WebShop\Exception\Security\User\UserNotLoggedInException;
use Silecust\WebShop\Repository\CustomerAddressRepository;
use Silecust\WebShop\Service\Security\User\Customer\CustomerFromUserFinder;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 *
 */
class CheckOutAddressQuery
{
    /**
     *
     */
    public const BILLING_ADDRESS_ID = "BILLING_ADDRESS_SET_ID";
    /**
     *
     */
    public const SHIPPING_ADDRESS_ID = "SHIPPING_ADDRESS_SET_ID";

    /**
     * @param CustomerAddressRepository $customerAddressRepository
     * @param CheckOutAddressSession    $checkOutAddressSession
     * @param CustomerFromUserFinder    $customerFromUserFinder
     * @param RequestStack              $requestStack
     */
    public function __construct(
        private readonly CustomerAddressRepository $customerAddressRepository,
        private readonly CheckOutAddressSession $checkOutAddressSession,
        private readonly CustomerFromUserFinder $customerFromUserFinder,
        RequestStack $requestStack
    ) {

    }

    /**
     * @param int $id
     *
     * @return bool
     * @throws UserNotAssociatedWithACustomerException
     * @throws UserNotLoggedInException
     */
    public function isAddressValid(int $id): bool
    {


        $addresses = $this->customerAddressRepository->findOneBy([
            'customer' => $this->customerFromUserFinder->getLoggedInCustomer(),
            'id'=>$id]);

         return $addresses != null;

    }


    /**
     * @param int $id
     *
     * @return void
     */
    public function setShippingAddress(int $id): void
    {

        // todo check address valid
        $this->checkOutAddressSession->setShippingAddress($id);
    }

    /**
     * @param int $id
     *
     * @return void
     */
    public function setBillingAddress(int $id): void
    {

        // todo check address valid
        $this->checkOutAddressSession->setBillingAddress($id);

    }


    /**
     * @return bool
     */
    public function isShippingAddressChosen(): bool
    {
        // todo check address valid

        return $this->checkOutAddressSession->isShippingAddressSet();
    }

    /**
     * @return bool
     */
    public function isBillingAddressChosen(): bool
    {

        // todo check address valid
        return $this->checkOutAddressSession->isBillingAddressSet();

    }

}