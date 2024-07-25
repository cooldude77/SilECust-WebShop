<?php

namespace App\Service\Transaction\Order\Item;

use App\Entity\Country;
use App\Entity\Currency;
use App\Entity\OrderItem;
use App\Exception\MasterData\Pricing\Item\PriceProductBaseNotFound;
use App\Service\MasterData\Pricing\PriceCalculator;

readonly class ItemPriceCalculator
{
    public function __construct(private PriceCalculator $priceCalculator)
    {
    }

    /**
     * @throws PriceProductBaseNotFound
     */
    public function getPriceWithoutTax(OrderItem $orderItem,Currency $currency): float
    {

       return $this->priceCalculator->calculatePriceWithoutTax($orderItem->getProduct(),$currency);

    }
    /**
     * @throws PriceProductBaseNotFound
     */
    public function getPriceWithTax(OrderItem $orderItem,Currency $currency,Country $country): float
    {

       return $this->priceCalculator->calculatePriceWithTax($orderItem->getProduct(),$currency,$country);

    }
}