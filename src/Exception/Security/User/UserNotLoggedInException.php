<?php

namespace Silecust\WebShop\Exception\Security\User;

use Exception;
use Throwable;

class UserNotLoggedInException extends Exception
{

public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
{
    parent::__construct($message, $code, $previous);
}
}