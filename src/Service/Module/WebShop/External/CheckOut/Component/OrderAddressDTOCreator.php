<?php

namespace App\Service\Module\WebShop\External\CheckOut\Component;

use App\Repository\CustomerAddressRepository;
use App\Repository\CustomerRepository;
use Symfony\Bundle\SecurityBundle\Security;

class OrderAddressDTOCreator
{

    public function __construct(private readonly CustomerRepository $customerRepository,
        private readonly CustomerAddressRepository $customerAddressRepository,
        private readonly Security $security)
    {
    }

    public function create()
    {

        $user = $this->security->getUser();
        $customer = $this->customerRepository->findOneBy(['user' => $user]);

        $addresses = $this->customerAddressRepository->findOneBy(['customer'=>$customer]);


    }
}