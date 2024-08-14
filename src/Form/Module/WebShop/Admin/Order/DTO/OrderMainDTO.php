<?php

namespace App\Form\Module\WebShop\Admin\Order\DTO;

class OrderMainDTO
{
    public int $id = 0;
    public OrderHeaderDTO $orderHeader;
    public $orderItemDto = array();
    public $orderAddressDto = array();

    public function __construct()
    {
        $this->orderHeader = new OrderHeaderDTO();
    }
}