<?php

namespace App\Service\Transaction\Order\Item;

use App\Entity\Country;
use App\Entity\Currency;
use App\Entity\OrderItem;
use App\Exception\MasterData\Pricing\Item\PriceProductBaseNotFound;
use App\Exception\MasterData\Pricing\Item\PriceProductTaxNotFound;
use App\Service\MasterData\Pricing\PriceByCountryCalculator;
use App\Service\MasterData\Pricing\PriceCalculator;

readonly class ItemPriceCalculator
{
    public function __construct(private PriceByCountryCalculator $priceByCountryCalculator)
    {
    }

    /**
     * @throws PriceProductBaseNotFound
     */
    public function getPriceWithoutTax(OrderItem $orderItem): float
    {

       return $this->priceByCountryCalculator->getPriceWithoutTax($orderItem->getProduct()->getId());

    }
    /**
     * @throws PriceProductBaseNotFound
     * @throws PriceProductTaxNotFound
     */
    public function getPriceWithTax(OrderItem $orderItem): float
    {

       return $this->priceByCountryCalculator->getPriceWithTax($orderItem->getProduct()->getId());

    }
}