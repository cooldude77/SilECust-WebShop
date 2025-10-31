<?php

namespace Silecust\WebShop\Exception\Module\WebShop\External\Product;

class InvalidCategorySearchFilter extends \Exception
{

    /**
     * @param string $string
     */
    public function __construct(string $string)
    {
        parent::__construct("Invalid search category criteria: $string");
    }
}