<?php

namespace Silecust\WebShop\Exception\MasterData\Pricing\Item;

use Silecust\WebShop\Entity\Product;

class PriceProductBaseNotFound extends \Exception
{

    /**
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        parent::__construct("Base Price not found for product {$product->getName()}");
    }
}