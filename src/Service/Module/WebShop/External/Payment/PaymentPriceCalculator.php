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

            $finalPrice += ($itemPaymentPrice->getBasePrice()-$itemPaymentPrice->getDiscount())
                *(1+$itemPaymentPrice->getTaxRate());
        }
        return $finalPrice;
    }
}