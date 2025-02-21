<?php

namespace Silecust\WebShop\Service\Transaction\Order\Admin\Header;

use Silecust\WebShop\Entity\OrderHeader;
use Silecust\WebShop\Exception\Transaction\Order\Admin\Header\OpenOrderEditedInAdminPanel;
use Silecust\WebShop\Service\Transaction\Order\Status\OrderStatusTypes;

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