<?php

namespace Silecust\WebShop\Exception\Security\User\Employee;

use Silecust\WebShop\Entity\User;
use Exception;

class UserNotAssociatedWithAnEmployeeException extends Exception
{

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        parent::__construct();
    }
}