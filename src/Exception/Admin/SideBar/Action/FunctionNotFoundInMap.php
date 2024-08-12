<?php

namespace App\Exception\Admin\SideBar\Action;

class FunctionNotFoundInMap extends
    \Exception
{
   public function __construct(string $fun)
   {
       parent::__construct("Function {$fun}not found ");
   }
}