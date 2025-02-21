<?php

namespace Silecust\WebShop\Service\Transaction\Order\Item\Mapper;

use Silecust\WebShop\Entity\OrderItem;
use Silecust\WebShop\Entity\OrderItemPaymentPrice;
use Silecust\WebShop\Exception\MasterData\Pricing\Item\PriceProductBaseNotFound;
use Silecust\WebShop\Exception\MasterData\Pricing\Item\PriceProductTaxNotFound;
use Silecust\WebShop\Repository\OrderItemPaymentPriceRepository;
use Silecust\WebShop\Service\Transaction\Order\Price\Item\ItemPriceCalculator;

readonly class OrderItemPaymentPriceMapper
{
    public function __construct(private ItemPriceCalculator             $priceCalculator,
                                private OrderItemPaymentPriceRepository $orderItemPaymentPriceRepository
    )
    {
    }

    /**
     * @param OrderItem $orderItem
     * @return OrderItemPaymentPrice
     * @throws PriceProductBaseNotFound
     * @throws PriceProductTaxNotFound
     */
    public function mapToEntityForCreate(OrderItem $orderItem): OrderItemPaymentPrice
    {

        $priceObject = $this->priceCalculator->getPriceObject($orderItem);

        return $this->orderItemPaymentPriceRepository->create($orderItem, $priceObject);

    }

    /**
     * @throws PriceProductTaxNotFound
     * @throws PriceProductBaseNotFound
     */
    public function mapToEntityForEdit(OrderItem $orderItem): OrderItemPaymentPrice
    {
        $priceObjectEntity = $this->orderItemPaymentPriceRepository->find($orderItem->getId());

        $priceObject = $this->priceCalculator->getPriceObject($orderItem);

        $priceObjectEntity->setBasePrice($priceObject->getBasePrice());
        $priceObjectEntity->setDiscount($priceObject->getDiscount());
        $priceObjectEntity->setRateOfTax($priceObject->getTaxRate());

        return $priceObjectEntity;
    }
}