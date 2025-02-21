<?php

namespace Silecust\WebShop\Exception\MasterData\Pricing\Item;

use Silecust\WebShop\Entity\Country;
use Silecust\WebShop\Entity\Product;

class PriceProductTaxNotFound extends \Exception
{
    private Country $country;

    /**
     * @param Product $product
     * @param Country $country
     */
    public function __construct(Product $product, Country $country)
    {
        parent::__construct("Tax rate not found for product {$product->getName()} for country {$country->getName()}");
        $this->country = $country;
    }
}