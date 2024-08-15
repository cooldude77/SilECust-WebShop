<?php

namespace App\Exception\Security\User;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

class UserNotAuthorized extends \Exception
{


    public function __construct(private readonly  UserInterface $user) {
        parent::__construct("User is not authorized for this url/action", 403, null);
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }


}