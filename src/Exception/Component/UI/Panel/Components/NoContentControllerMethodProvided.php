<?php

namespace Silecust\WebShop\Exception\Component\UI\Panel\Components;

class NoContentControllerMethodProvided extends \Exception
{
    public function __construct(string $className)
    {
        parent::__construct($className . "\'s method not provided");
    }

}