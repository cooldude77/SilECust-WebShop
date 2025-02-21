<?php

namespace Silecust\WebShop\Service\Admin\Employee\Common;

use Silecust\WebShop\Entity\Category;
use Silecust\WebShop\Entity\Product;
use Silecust\WebShop\Exception\Admin\Common\FunctionNotMappedToAnyEntity;

class FunctionToEntityMapper
{

    public function map(string $function)
    {
        switch ($function) {
            case 'category':
                return Category::class;
            case 'product':
                return Product::class;
        }

        throw new FunctionNotMappedToAnyEntity($function);
    }
}