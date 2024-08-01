<?php

namespace App\Exception\Command\Security\User;

class CommandNotAvailableOutsideDev extends \Exception
{

 public function __construct(string $message )
 {
     parent::__construct('Command '.$message.' is not available outside dev and test env');
 }
}