<?php

namespace App\Service\Module\WebShop\External\Cart\Session\Object;

class CartSessionObject
{

    public function __construct(public string $productId, public int $quantity = 0)
    {
    }


    public function increaseQuantityBy(int $increaseBy = 1): void
    {
        //todo: check max quantity
        $this->quantity += $increaseBy;

    }

    public function decreaseQuantityBy(int $decreaseBy = 1): void
    {
        //todo: check quantity 0;
        $this->quantity -= $decreaseBy;

    }

    /**
     * @return array
     *
     * For proper storage and retrieval from session
     */
    public function __serialize(): array
    {

        return [
            'productId' => $this->productId,
            'quantity' => $this->quantity,
        ];
    }

    /**
     * @param array $data
     *
     * @return void
     *
     *  For proper storage and retrieval from session
     */
    public function __unserialize(array $data): void
    {

        $this->productId = $data['productId'];
        $this->quantity = $data['quantity'];

    }
}