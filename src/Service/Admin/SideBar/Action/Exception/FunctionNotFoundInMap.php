<?php

namespace App\Service\Admin\SideBar\Action\Exception;

class FunctionNotFoundInMap extends
    \Exception
{
   public function __construct(string $fun)
   {
       parent::__construct("Function {$fun}not found ");
   }
}