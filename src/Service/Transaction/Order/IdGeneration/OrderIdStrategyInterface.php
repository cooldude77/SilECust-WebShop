<?php

namespace App\Service\Transaction\Order\IdGeneration;

interface OrderIdStrategyInterface
{

    public function generateOrderId(): string;
}