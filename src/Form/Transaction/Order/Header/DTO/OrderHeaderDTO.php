<?php

namespace Silecust\WebShop\Form\Transaction\Order\Header\DTO;

class OrderHeaderDTO
{

    public int $id = 0;
    public int $orderStatusTypeId = 0;

    public ?string $changeNote = null;
}