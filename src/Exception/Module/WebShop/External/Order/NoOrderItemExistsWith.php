<?php

namespace App\Exception\Module\WebShop\External\Order;

use App\Entity\Customer;
use App\Entity\Product;
use Exception;

class NoOrderItemExistsWith extends Exception
{

    /**
     * @param Customer $getCustomer
     * @param Product  $getProduct
     * @param int                  $getQuantity
     */
    public function __construct(public Customer $getCustomer,
        public Product $getProduct, public int $getQuantity
    ) {
        parent::__construct();
    }
}