<?php

namespace Silecust\WebShop\Service\Testing\Fixtures;

use Silecust\WebShop\Entity\OrderItem;
use Silecust\WebShop\Factory\OrderItemFactory;
use Zenstruck\Foundry\Proxy;

trait OrderItemFixture
{

    private int $quantityForOpenOrderAProductA = 10;
    private int $quantityForOpenOrderAProductB = 20;

    private Proxy|OrderItem $orderItem1ForOpenOrderA;

    private Proxy|OrderItem $orderItem2ForOpenOrderA;

    private int $quantityForInProcessOrderAProduct1 = 10;
    private int $quantityForInProcessOrderAProduct2 = 20;


    private Proxy|OrderItem $orderItem1ForInProcessOrderA;

    private Proxy|OrderItem $orderItem2ForInProcessOrderA;

    public function createOpenOrderItemsFixtureA(
        Proxy $orderHeaderA,
        Proxy $product1, Proxy $product2
    ): void
    {
        $this->orderItem1ForOpenOrderA = OrderItemFactory::createOne([
            'orderHeader' => $orderHeaderA,
            'product' => $product1,
            'quantity' => $this->quantityForOpenOrderAProductA]);

        $this->orderItem2ForOpenOrderA = OrderItemFactory::createOne([
            'orderHeader' => $orderHeaderA,
            'product' => $product2,
            'quantity' => $this->quantityForOpenOrderAProductB]);

    }

    public function createInProcessOrderItemsFixtureA(
        Proxy $orderHeaderA,
        Proxy $product1,
        Proxy $product2
    ): void
    {
        $this->orderItem1ForInProcessOrderA = OrderItemFactory::createOne([
            'orderHeader' => $orderHeaderA,
            'product' => $product1,
            'quantity' => $this->quantityForInProcessOrderAProduct1]);

        $this->orderItem2ForInProcessOrderA = OrderItemFactory::createOne([
            'orderHeader' => $orderHeaderA,
            'product' => $product2,
            'quantity' => $this->quantityForInProcessOrderAProduct2]);

    }


}