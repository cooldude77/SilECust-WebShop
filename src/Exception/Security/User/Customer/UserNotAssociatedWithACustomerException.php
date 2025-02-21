<?php

namespace Silecust\WebShop\Exception\Security\User\Customer;

use Silecust\WebShop\Entity\User;
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