<?php

namespace App\Tests\Fixtures;

use App\Entity\OrderItem;
use App\Factory\OrderItemFactory;
use Zenstruck\Foundry\Proxy;

trait OrderItemFixture
{

    private int $quantityA = 10;
    private int $quantityB = 20;

    private Proxy|OrderItem $orderItemA;

    private Proxy|OrderItem $orderItemB;

    public function createOrderItemsFixture(Proxy $orderHeader,
        Proxy $productA, Proxy $productB
    ): void {
        $this->orderItemA = OrderItemFactory::createOne([
            'orderHeader' => $orderHeader,
            'product' => $productA,
            'quantity' => $this->quantityA]);
        $this->orderItemB = OrderItemFactory::createOne([
            'orderHeader' => $orderHeader,
            'product' => $productB,
            'quantity' => $this->quantityB]);

    }


}