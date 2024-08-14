<?php

namespace App\Exception\Transaction\Order;

use App\Entity\Customer;
use App\Entity\OrderHeader;

class NoCurrencyFoundBecauseNoShippingAddressExist extends \Exception
{

    private Customer $customer;
    private OrderHeader $orderHeader;

    public function __construct(Customer $customer,OrderHeader $orderHeader)
    {
        parent::__construct();
        $this->customer = $customer;
        $this->orderHeader = $orderHeader;
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function getOrderHeader(): OrderHeader
    {
        return $this->orderHeader;
    }


}