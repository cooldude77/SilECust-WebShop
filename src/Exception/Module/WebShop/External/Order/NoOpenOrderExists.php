<?php

namespace App\Exception\Module\WebShop\External\Order;

use App\Entity\Customer;
use Exception;

class NoOpenOrderExists extends Exception
{
    /**
     * @param Customer $getCustomer
     */
    public function __construct(public Customer $getCustomer
    ) {
        parent::__construct();
    }
}