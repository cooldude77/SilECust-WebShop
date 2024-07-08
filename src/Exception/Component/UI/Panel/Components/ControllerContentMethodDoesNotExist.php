<?php

namespace App\Exception\Component\UI\Panel\Components;

class ControllerContentMethodDoesNotExist extends \Exception
{

    public function __construct(string $className,string $methodName)
    {
        parent::__construct("\"{$className}\" exists but method \"{$methodName}\" does not exist");
    }

}