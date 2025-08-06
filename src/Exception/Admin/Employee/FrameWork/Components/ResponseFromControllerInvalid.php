<?php

namespace Silecust\WebShop\Exception\Admin\Employee\FrameWork\Components;

use Exception;

class ResponseFromControllerInvalid extends Exception
{

    /**
     * @param string $string
     */
    public function __construct(string $string)
    {
        parent::__construct($string);
    }
}