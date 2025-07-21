<?php

namespace Silecust\WebShop\Service\Transaction\Order\Admin\Item\Changes;

use Silecust\WebShop\Entity\OrderItem;
use Silecust\WebShop\Service\Transaction\Order\Journal\OrderJournalChangesType;

class ChangedDataFinder
{

    public function getChangedData(OrderItem $orderItem, array $requestData): array
    {
        $changedArray = [];
        if ($orderItem->getQuantity() != $requestData['quantity']) {
            $changedArray[OrderJournalChangesType::ITEM_QUANTITY_CHANGED] =
                [
                    OrderJournalChangesType::OLD_VALUE => $orderItem->getQuantity(),
                    OrderJournalChangesType::NEW_VALUE => $requestData['quantity']
                ];
        }

        return $changedArray;

    }
}