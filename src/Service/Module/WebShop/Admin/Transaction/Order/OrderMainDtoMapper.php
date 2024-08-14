<?php

namespace App\Service\Module\WebShop\Admin\Transaction\Order;

class OrderMainDtoMapper
{
    public function __construct(readonly private OrderHeaderDtoMapper $orderHeaderDtoMapper,
        readonly private OrderItemDtoMapper $orderItemDtoMapper,
        readonly private OrderAddressDtoMapper $orderAddressDtoMapper
    ) {
    }
}