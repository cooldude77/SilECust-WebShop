<?php

namespace App\Service\Transaction\Order\Price\Header;

use App\Entity\OrderHeader;
use App\Entity\OrderShipping;
use App\Repository\OrderShippingRepository;

readonly class ShippingChargeCalculator
{

    public function __construct(private OrderShippingRepository $orderShippingRepository)
    {
    }

    public function getShippingCharges(OrderHeader $orderHeader): float
    {

        $shipping = $this->orderShippingRepository->findAll();
      //  findBy(['orderHeader' => $orderHeader]);

        $final = 0;

        /** @var OrderShipping $data */
        foreach ($shipping as $data) {
            $final += $data->getValue();
        }

        return $final;
    }
}