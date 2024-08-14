<?php

namespace App\Service\Module\WebShop\External\Payment;

use App\Entity\OrderItemPaymentPrice;

class PaymentPriceCalculator
{

    public function calculateOrderPaymentPrice(array $orderItemPaymentPrices): float|int
    {
        $finalPrice = 0;
        /** @var OrderItemPaymentPrice $itemPaymentPrice */
        foreach ($orderItemPaymentPrices as $itemPaymentPrice){

            $discountPercentage = $itemPaymentPrice != null ?$itemPaymentPrice->getDiscount():0;

            $unitFinalPrice =(
                $itemPaymentPrice->getBasePrice() *(1- $discountPercentage/100 )
                )
                *
                (1+$itemPaymentPrice->getTaxRate()/100);

            $totalItemPrice = $unitFinalPrice*$itemPaymentPrice->getOrderItem()->getQuantity();

            $finalPrice += $totalItemPrice;
        }
        return $finalPrice;
    }
}