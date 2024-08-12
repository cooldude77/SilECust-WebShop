<?php

namespace App\Exception\Security\User\Employee;

use App\Entity\User;
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