<?php

namespace Silecust\WebShop\Service\Transaction\Order\IdGeneration;

use Symfony\Component\String\ByteString;

class OrderIdStrategy implements OrderIdStrategyInterface
{

    public function generateOrderId(): string
    {
        return ByteString::fromRandom(12);
    }
}