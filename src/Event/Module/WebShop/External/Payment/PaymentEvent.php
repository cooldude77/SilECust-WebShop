<?php

namespace App\Event\Module\WebShop\External\Payment;

use App\Entity\Customer;
use App\Exception\Security\User\Customer\UserNotAssociatedWithACustomerException;
use App\Exception\Security\User\UserNotLoggedInException;
use App\Service\Security\User\Customer\CustomerFromUserFinder;
use Symfony\Contracts\EventDispatcher\Event;

class PaymentEvent extends Event
{

    private mixed $paymentData;
    private Customer $customer;

    public function __construct() {
    }

    public function getPaymentData(): mixed
    {
        return $this->paymentData;
    }

    /**
     * @throws UserNotAssociatedWithACustomerException
     * @throws UserNotLoggedInException
     */
    public function getCustomer(): \App\Entity\Customer
    {
     return   $this->customer;
    }

    /**
     * @param mixed $paymentData
     */
    public function setPaymentData($paymentData): void
    {
        $this->paymentData = $paymentData;
    }

    /**
     * @param mixed $customer
     */
    public function setCustomer($customer): void
    {
        $this->customer = $customer;
    }


}