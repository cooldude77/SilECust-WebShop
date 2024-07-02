<?php

namespace App\Exception\Security\User\Customer;

use App\Entity\User;
use Exception;

class UserNotAssociatedWithACustomerException extends Exception
{

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        parent::__construct();
    }
}