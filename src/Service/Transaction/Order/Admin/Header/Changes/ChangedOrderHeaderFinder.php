<?php

namespace Silecust\WebShop\Service\Transaction\Order\Admin\Header\Changes;

use Silecust\WebShop\Entity\OrderHeader;
use Silecust\WebShop\Service\Transaction\Order\Journal\OrderJournalChangesType;

class ChangedOrderHeaderFinder
{

    public function getChangedData(OrderHeader $orderHeader, array $requestData): array
    {
        $changedArray = [];
        if ($orderHeader->getOrderStatusType()->getId() != $requestData['orderStatusTypeId']) {
            $changedArray[OrderJournalChangesType::ORDER_STATUS_CHANGED] =
                [
                    OrderJournalChangesType::OLD_VALUE => $orderHeader->getOrderStatusType()->getId(),
                    OrderJournalChangesType::NEW_VALUE => $requestData['orderStatusTypeId']
                ];
        }

        return $changedArray;

    }
}