<?php

namespace Silecust\WebShop\Service\Transaction\Order\Price\Header;

use Silecust\WebShop\Entity\OrderHeader;
use Silecust\WebShop\Entity\OrderItem;
use Silecust\WebShop\Exception\MasterData\Pricing\Item\PriceProductBaseNotFound;
use Silecust\WebShop\Exception\MasterData\Pricing\Item\PriceProductTaxNotFound;
use Silecust\WebShop\Service\MasterData\Price\PriceByCountryCalculator;
use Silecust\WebShop\Service\Transaction\Order\OrderRead;

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