<?php

namespace App\Exception\MasterData\Pricing\Item;

use App\Entity\Country;
use App\Entity\Product;

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