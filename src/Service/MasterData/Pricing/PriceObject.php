<?php

namespace App\Service\MasterData\Pricing;

use App\Entity\PriceProductBase;
use App\Entity\PriceProductDiscount;
use App\Entity\PriceProductTax;

class PriceObject
{
    private PriceProductBase $priceProductBase;

    private PriceProductDiscount $priceProductDiscount;

    private PriceProductTax $priceProductTax;

    public function __construct()
    {
    }

    /**
     * @return PriceProductBase
     */
    public function getPriceProductBase(): PriceProductBase
    {
        return $this->priceProductBase;
    }

    /**
     * @param PriceProductBase $priceProductBase
     */
    public function setPriceProductBase(PriceProductBase $priceProductBase): void
    {
        $this->priceProductBase = $priceProductBase;
    }

    /**
     * @return PriceProductDiscount
     */
    public function getPriceProductDiscount(): PriceProductDiscount
    {
        return $this->priceProductDiscount;
    }

    /**
     * @param PriceProductDiscount $priceProductDiscount
     */
    public function setPriceProductDiscount(PriceProductDiscount $priceProductDiscount = null): void
    {
        $this->priceProductDiscount = $priceProductDiscount;
    }

    /**
     * @return PriceProductTax
     */
    public function getPriceProductTax(): PriceProductTax
    {
        return $this->priceProductTax;
    }

    /**
     * @param PriceProductTax $priceProductTax
     */
    public function setPriceProductTax(PriceProductTax $priceProductTax): void
    {
        $this->priceProductTax = $priceProductTax;
    }


}