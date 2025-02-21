<?php

namespace Silecust\WebShop\Exception\Module\WebShop\External\Order;

use Silecust\WebShop\Entity\Customer;
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