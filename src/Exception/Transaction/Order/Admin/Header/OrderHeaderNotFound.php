<?php

namespace Silecust\WebShop\Exception\Transaction\Order\Admin\Header;

class OrderHeaderNotFound extends \Exception
{

    /**
     * @param string[] $array
     */
    public function __construct(private  readonly array $array)
    {
        parent::__construct();
    }
}