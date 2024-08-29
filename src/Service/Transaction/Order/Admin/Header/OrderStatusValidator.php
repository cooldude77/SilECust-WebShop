<?php

namespace App\Service\Transaction\Order\Admin\Header;

use App\Entity\OrderHeader;
use App\Exception\Transaction\Order\Admin\Header\OpenOrderEditedInAdminPanel;
use App\Service\Transaction\Order\Status\OrderStatusTypes;

class OrderStatusValidator
{


    /**
     * @throws OpenOrderEditedInAdminPanel
     */
    public function checkOrderStatus(OrderHeader $orderHeader, string $operation): void
    {

        if ($operation == 'edit')
            if ($orderHeader->getOrderStatusType()->getType() == OrderStatusTypes::ORDER_CREATED)
                throw new OpenOrderEditedInAdminPanel($orderHeader);
        if ($operation == 'display')
            if ($orderHeader->getOrderStatusType()->getType() == OrderStatusTypes::ORDER_CREATED)
                throw new OpenOrderDisplayedInAdminPanel($orderHeader);
    }
}