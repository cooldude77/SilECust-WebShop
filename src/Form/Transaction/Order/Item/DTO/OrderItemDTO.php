<?php

namespace App\Form\Transaction\Order\Item\DTO;

class OrderItemDTO
{

    private ?int $id = 0;

    public ?int $orderHeaderId = 0;

    public ?int $productId = 0;

    public ?int $quantity = 0;

}