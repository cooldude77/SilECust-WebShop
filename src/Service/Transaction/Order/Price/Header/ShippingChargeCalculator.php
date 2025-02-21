<?php

namespace Silecust\WebShop\Service\Transaction\Order\Price\Header;

use Silecust\WebShop\Entity\OrderHeader;
use Silecust\WebShop\Entity\OrderShipping;
use Silecust\WebShop\Repository\OrderShippingRepository;

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