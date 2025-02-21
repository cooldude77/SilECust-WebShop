<?php

namespace Silecust\WebShop\Service\Transaction\Order\Price\Item;

use Silecust\WebShop\Entity\Currency;
use Silecust\WebShop\Entity\OrderItem;
use Silecust\WebShop\Exception\MasterData\Pricing\Item\PriceProductBaseNotFound;
use Silecust\WebShop\Exception\MasterData\Pricing\Item\PriceProductTaxNotFound;
use Silecust\WebShop\Service\MasterData\Price\PriceByCountryCalculator;

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

        return $this->priceByCountryCalculator->getPriceWithoutTax(
            $orderItem->getProduct()->getId()
        );

    }

    /**
     * @throws PriceProductBaseNotFound
     * @throws PriceProductTaxNotFound
     */
    public function getPriceWithTax(OrderItem $orderItem): float
    {

        return $this->priceByCountryCalculator->getPriceWithTax($orderItem->getProduct()->getId());

    }

    public function getCurrency(): Currency
    {
        return $this->priceByCountryCalculator->getCurrency();
    }

    /**
     * @throws PriceProductTaxNotFound
     * @throws PriceProductBaseNotFound
     */
    public function getPriceObject(OrderItem $orderItem): \Silecust\WebShop\Service\Transaction\Order\PriceObject
    {
        return $this->priceByCountryCalculator->getPriceObject($orderItem);
    }

}