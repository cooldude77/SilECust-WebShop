<?php

namespace Silecust\WebShop\Form\Transaction\Order\Header\DTO;

class OrderHeaderDTO
{

    public int $id = 0;

    public ?string $guid = null;
    public int $customerId = 0;

    public ?string $dateTimeOfOrder = null;

    public int $orderStatusTypeId = 0;

    public ?string $note = null;
}