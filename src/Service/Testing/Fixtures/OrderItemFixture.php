<?php

namespace Silecust\WebShop\Service\Testing\Fixtures;

use Silecust\WebShop\Entity\OrderItem;
use Silecust\WebShop\Factory\OrderItemFactory;
use Zenstruck\Foundry\Proxy;

trait OrderItemFixture
{

    private int $quantityForOpenOrderA = 10;
    private int $quantityForOpenOrderB = 20;

    private Proxy|OrderItem $orderItemAForOpenOrder;

    private Proxy|OrderItem $orderItemBForOpenOrder;

    private int $quantityForInProcessOrderA = 10;
    private int $quantityForInProcessOrderB = 20;

    private Proxy|OrderItem $orderItemAForInProcessOrder;

    private Proxy|OrderItem $orderItemBForInProcessOrder;

    public function createOpenOrderItemsFixture(
        Proxy $orderHeader,
        Proxy $productA, Proxy $productB
    ): void
    {
        $this->orderItemAForOpenOrder = OrderItemFactory::createOne([
            'orderHeader' => $orderHeader,
            'product' => $productA,
            'quantity' => $this->quantityForOpenOrderA]);

        $this->orderItemBForOpenOrder = OrderItemFactory::createOne([
            'orderHeader' => $orderHeader,
            'product' => $productB,
            'quantity' => $this->quantityForOpenOrderB]);

    }

    public function createInProcessOrderItemsFixture(
        Proxy $orderHeader,
        Proxy $productA,
        Proxy $productB
    ): void
    {
        $this->orderItemAForInProcessOrder = OrderItemFactory::createOne([
            'orderHeader' => $orderHeader,
            'product' => $productA,
            'quantity' => $this->quantityForInProcessOrderA]);

        $this->orderItemBForInProcessOrder = OrderItemFactory::createOne([
            'orderHeader' => $orderHeader,
            'product' => $productB,
            'quantity' => $this->quantityForInProcessOrderB]);

    }


}