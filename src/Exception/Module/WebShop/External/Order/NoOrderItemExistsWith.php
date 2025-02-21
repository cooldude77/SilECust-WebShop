<?php

namespace Silecust\WebShop\Exception\Module\WebShop\External\Order;

use Silecust\WebShop\Entity\Customer;
use Silecust\WebShop\Entity\Product;
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