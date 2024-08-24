<?php

namespace App\Service\Transaction\Order\Item\Mapper;

use App\Entity\OrderItem;
use App\Entity\OrderItemPriceBreakup;
use App\Entity\Product;
use App\Repository\OrderItemPriceBreakupRepository;
use App\Service\Transaction\Order\Price\Item\ItemPriceCalculator;

class OrderItemPriceBreakupMapper
{
    public function __construct(private readonly ItemPriceCalculator             $priceCalculator,
                                private readonly OrderItemPriceBreakupRepository $orderItemPriceBreakupRepository
    )
    {
    }

    /**
     * @param Product|null $product
     *
     * @return OrderItemPriceBreakup
     */
    public function mapToEntityForCreate(OrderItem $orderItem): OrderItemPriceBreakup
    {

        $priceObject = $this->priceCalculator->getPriceObject($orderItem);

        return $this->orderItemPriceBreakupRepository->create($orderItem, $priceObject);

    }

    public function mapToEntityForEdit(OrderItem $orderItem): OrderItemPriceBreakup
    {
        $priceObjectEntity = $this->orderItemPriceBreakupRepository->find($orderItem->getId());

        $priceObject = $this->priceCalculator->getPriceObject($orderItem);

        $priceObjectEntity->setBasePrice($priceObject->getBasePrice());
        $priceObjectEntity->setDiscount($priceObject->getDiscount());
        $priceObjectEntity->setRateOfTax($priceObject->getTaxRate());

        return $priceObjectEntity;
    }
}