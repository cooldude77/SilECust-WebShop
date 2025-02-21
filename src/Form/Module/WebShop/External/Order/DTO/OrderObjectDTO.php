<?php

namespace Silecust\WebShop\Form\Module\WebShop\External\Order\DTO;

use Silecust\WebShop\Form\Module\WebShop\External\Order\DTO\Components\OrderHeaderDTO;
use Silecust\WebShop\Form\Module\WebShop\External\Order\DTO\Components\OrderPaymentDTO;

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

    public function add(\Silecust\WebShop\Form\Transaction\Order\Item\DTO\OrderItemDTO $orderItemDTO)
    {
        $orderItemDTO[] = $orderItemDTO;
    }
}