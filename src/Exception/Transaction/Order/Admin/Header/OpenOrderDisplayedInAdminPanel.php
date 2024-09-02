<?php

namespace App\Exception\Transaction\Order\Admin\Header;

use App\Entity\OrderHeader;
use Exception;

class OpenOrderDisplayedInAdminPanel extends Exception
{

    /**
     * @param OrderHeader $orderHeader
     */
    public function __construct(private  readonly OrderHeader $orderHeader)
    {
        parent::__construct("Open orders are implicit orders in the cart. They can only be manipulated by customers who is operating the cart");
    }

    public function getOrderHeader(): OrderHeader
    {
        return $this->orderHeader;
    }
}