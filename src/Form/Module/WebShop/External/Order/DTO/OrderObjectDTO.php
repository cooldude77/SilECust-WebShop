<?php

namespace App\Form\Module\WebShop\External\Order\DTO;

use App\Form\Module\WebShop\External\Order\DTO\Components\OrderHeaderDTO;
use App\Form\Module\WebShop\External\Order\DTO\Components\OrderPaymentDTO;

class OrderObjectDTO
{

    public OrderHeaderDTO $orderHeaderDTO;
    public $orderItemDTOArray = array();
    public OrderPaymentDTO $orderPaymentDTO;

    public $orderAddressDTOArray = array();

    public function __construct()
    {
        $this->orderHeaderDTO = new OrderHeaderDTO();
        $this->orderPaymentDTO = new OrderPaymentDTO();
    }

    public function add(\App\Form\Transaction\Order\Item\DTO\OrderItemDTO $orderItemDTO)
    {
        $orderItemDTO[] = $orderItemDTO;
    }
}