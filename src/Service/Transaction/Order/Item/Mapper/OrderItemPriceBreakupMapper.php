<?php

namespace App\Service\Transaction\Order\Item\Mapper;

use App\Entity\OrderItem;
use App\Entity\OrderItemPriceBreakup;
use App\Entity\Product;
use App\Repository\OrderItemPriceBreakupRepository;
use App\Service\MasterData\Pricing\Item\PriceBreakUpEntityFinder;

class OrderItemPriceBreakupMapper
{
    /**
     * @param PriceBreakUpEntityFinder $priceCalculator
     */
    public function __construct(private readonly PriceBreakUpEntityFinder $priceCalculator,
        private readonly OrderItemPriceBreakupRepository $orderItemPriceBreakupRepository
    ) {
    }

    /**
     * @param Product|null $product
     *
     * @return OrderItemPriceBreakup
     */
    public function mapToEntityForCreate(OrderItem $orderItem): OrderItemPriceBreakup
    {

        $priceObject = $this->priceCalculator->getPriceObject($orderItem->getProduct());

        return $this->orderItemPriceBreakupRepository->create(
            $orderItem,
            []
        );

    }
}