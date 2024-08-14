<?php

namespace App\Form\Module\WebShop\Admin\Order\DTO;

class OrderHeaderDTO
{
    public int $id = 0;
    public int $customerId = 0;

    public ?string $dateTimeOfOrder = null;
}