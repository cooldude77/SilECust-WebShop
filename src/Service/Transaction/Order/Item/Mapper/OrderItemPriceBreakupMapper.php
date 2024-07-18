<?php

namespace App\Service\Transaction\Order\Item\Mapper;

use App\Entity\OrderItem;
use App\Entity\OrderItemPriceBreakup;
use App\Entity\Product;
use App\Repository\OrderItemPriceBreakupRepository;
use App\Service\MasterData\Pricing\PriceCalculator;

class OrderItemPriceBreakupMapper
{
    /**
     * @param PriceCalculator $priceCalculator
     */
    public function __construct(private readonly PriceCalculator $priceCalculator,
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
            [
                OrderItemPriceBreakup::BASE_PRICE => $priceObject->getPriceProductBase()
                    ->getPrice(),
                OrderItemPriceBreakup::DISCOUNT => $priceObject->getPriceProductDiscount()
                    ->getValue(),
                OrderItemPriceBreakup::RATE_OF_TAX => $priceObject->getPriceProductTax()->getTaxSlab
                ()->getRateOfTax()

            ]
        );

    }
}