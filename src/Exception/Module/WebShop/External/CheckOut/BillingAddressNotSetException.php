<?php

namespace Silecust\WebShop\Exception\Module\WebShop\External\CheckOut;


use Exception;
use Throwable;

class BillingAddressNotSetException extends Exception
{

 public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
 {
     parent::__construct($message, $code, $previous);
 }
}