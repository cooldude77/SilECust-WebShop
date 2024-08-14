<?php

namespace App\Event\Security\External\SignUp;

use App\Entity\Customer;
use Symfony\Contracts\EventDispatcher\Event;

class SignUpEvent extends Event
{

    private Customer $customer;


    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function setCustomer(Customer $customer): void
    {
        $this->customer = $customer;
    }
}