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

   // todo: make sure that product without pricing and taxes give a warning
    // or provide a setting
    /**
     * @return PriceProductBase
     */
    public function getPriceProductBase(): PriceProductBase
    {
        return $this->priceProductBase;
    }

    /**
     * @param PriceProductBase|null $priceProductBase
     */
    public function setPriceProductBase(?PriceProductBase $priceProductBase = null): void
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
     * @param PriceProductDiscount|null $priceProductDiscount
     */
    public function setPriceProductDiscount(?PriceProductDiscount $priceProductDiscount = null):
    void {
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
     * @param PriceProductTax|null $priceProductTax
     */
    public function setPriceProductTax(?PriceProductTax $priceProductTax = null): void
    {
        $this->priceProductTax = $priceProductTax;
    }


}