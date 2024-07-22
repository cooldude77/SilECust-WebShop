<?php

namespace App\Service\Transaction\Order\Item;

use App\Service\MasterData\Pricing\Item\PriceCalculator;

class ItemPriceCalculator
{
    public function __construct(private PriceCalculator $priceCalculator)
    {
    }

    public function getItemPrice(OrderItem $orderItem)
    {

    }
}