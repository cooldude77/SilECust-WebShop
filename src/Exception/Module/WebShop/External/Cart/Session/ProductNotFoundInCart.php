<?php

namespace App\Exception\Module\WebShop\External\Cart\Session;

class ProductNotFoundInCart extends \Exception
{

    /**
     * @param string $id
     */
    public function __construct(string $id)
    {
        parent::__construct("Product with $id not found");
    }
}