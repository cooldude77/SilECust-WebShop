<?php

namespace App\Service\Transaction\Order\Price\Header;

use App\Entity\OrderHeader;
use App\Entity\OrderItem;
use App\Exception\MasterData\Pricing\Item\PriceProductBaseNotFound;
use App\Exception\MasterData\Pricing\Item\PriceProductTaxNotFound;
use App\Service\MasterData\Price\PriceByCountryCalculator;
use App\Service\Transaction\Order\OrderRead;

readonly class HeaderPriceCalculator
{


    public function __construct(
        private OrderRead                $orderRead,
        private PriceByCountryCalculator $priceByCountryCalculator,
        private ShippingChargeCalculator $shippingPriceCalculator)
    {

    }

    /**
     * @throws PriceProductTaxNotFound
     * @throws PriceProductBaseNotFound
     */
    public function calculateOrderValue(OrderHeader $header): float
    {

        $items = $this->orderRead->getOrderItems($header);

        $totalPrice = 0;
        /** @var OrderItem $item */
        foreach ($items as $item) {
            $totalPrice += $this->priceByCountryCalculator->getPriceWithTax($item->getProduct()->getId()) * $item->getQuantity();

        }

        $totalPrice += $this->shippingPriceCalculator->getShippingCharges($header);

        return $totalPrice;
    }
}