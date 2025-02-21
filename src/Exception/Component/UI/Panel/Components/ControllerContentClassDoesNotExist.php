<?php

namespace Silecust\WebShop\Exception\Component\UI\Panel\Components;

class ControllerContentClassDoesNotExist extends \Exception
{

    public function __construct(string $className)
    {
        parent::__construct($className . " does not exist");
    }

}