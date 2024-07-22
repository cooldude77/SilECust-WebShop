<?php

namespace App\Service\Transaction\Order\Item;

use App\Entity\OrderItem;
use App\Service\MasterData\Pricing\Item\PriceBreakUpEntityFinder;
use App\Service\MasterData\Pricing\Item\PriceCalculator;

readonly class ItemPriceCalculator
{
    public function __construct(private PriceCalculator $priceCalculator,
    private PriceBreakUpEntityFinder $priceBreakUp)
    {
    }

    public function getPrice(OrderItem $orderItem): float
    {

       return $this->priceCalculator->calculatePrice(
           $this->priceBreakUp->getPriceObject($orderItem->getProduct()));

    }
}