<?php

namespace App\Service\Module\WebShop\External\CheckOut\Component;

use App\Form\Module\WebShop\External\Order\DTO\Components\OrderHeaderDTO;
use App\Repository\CustomerRepository;
use Symfony\Bundle\SecurityBundle\Security;

class OrderHeaderDTOCreator
{

    public function __construct(private readonly CustomerRepository $customerRepository,
        private readonly Security $security
    ) {

    }

    public function create(): OrderHeaderDTO
    {
        $orderHeaderDTO = new OrderHeaderDTO();
        $user = $this->security->getUser();
        $customer = $this->customerRepository->findOneBy(['user' => $user]);

        $orderHeaderDTO->customerId = $customer->getId();
        $orderHeaderDTO->dateTimeOfOrder = date_format(
            new \DateTime(), "Y/m/d H:i:s"
        );;


        return $orderHeaderDTO;
    }
}