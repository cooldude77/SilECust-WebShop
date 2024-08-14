<?php

namespace App\Service\Admin\Employee\Common;

use App\Entity\Category;
use App\Entity\Product;
use App\Exception\Admin\Common\FunctionNotMappedToAnyEntity;

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