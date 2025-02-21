<?php

namespace Silecust\WebShop\Service\Transaction\Order\IdGeneration;

interface OrderIdStrategyInterface
{

    public function generateOrderId(): string;
}