<?php

namespace Silecust\WebShop\Exception\Admin\SideBar\Action;

use Exception;

class EmptyActionListMapException extends
    Exception
{
    public function __construct(string $message = "Empty Action List")
    {
        parent::__construct($message);
    }
}