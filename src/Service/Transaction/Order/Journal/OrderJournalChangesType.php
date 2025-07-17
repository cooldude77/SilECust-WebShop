<?php

namespace Silecust\WebShop\Service\Transaction\Order\Journal;

class OrderJournalChangesType
{
    const string ORDER_STATUS_CHANGED = 'order_status_changed';
    const string OLD_VALUE = 'old_value';
    const string NEW_VALUE = 'new_value';
}