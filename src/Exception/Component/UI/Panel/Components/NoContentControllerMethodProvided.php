<?php

namespace App\Exception\Component\UI\Panel\Components;

class NoContentControllerMethodProvided extends \Exception
{
    public function __construct(string $className)
    {
        parent::__construct($className . "\'s method not provided");
    }

}