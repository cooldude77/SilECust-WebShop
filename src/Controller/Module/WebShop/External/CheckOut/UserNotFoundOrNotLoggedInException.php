<?php

namespace App\Controller\Module\WebShop\External\CheckOut;

use Doctrine\DBAL\Exception;

class UserNotFoundOrNotLoggedInException extends Exception
{

   public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
   {
       parent::__construct($message, $code, $previous);
   }
}