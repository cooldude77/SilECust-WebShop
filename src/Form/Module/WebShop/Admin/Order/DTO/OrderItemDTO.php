<?php

namespace App\Form\Module\WebShop\Admin\Order\DTO;

class OrderItemDTO
{
    public int $id = 0;
    public int $orderId = 0;

    public int $productId = 0;

    public int $quantity = 0;

    public int $pricePerUnit = 0;
}