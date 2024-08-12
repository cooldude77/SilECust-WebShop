<?php

namespace App\Service\Admin\SideBar\Action\Exception;

use Exception;

class EmptyActionListMapException extends
    Exception
{
    public function __construct(string $message = "Empty Action List")
    {
        parent::__construct($message);
    }
}