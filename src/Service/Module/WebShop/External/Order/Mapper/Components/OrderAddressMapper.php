<?php

namespace App\Service\Module\WebShop\External\Order\Mapper\Components;

use Doctrine\Common\Collections\ArrayCollection;

class OrderAddressMapper
{
    public function
    {
        $orderAddresses
            = new ArrayCollection();


        foreach ($orderObjectDTO->orderAddressDTOArray as $item) {

            $orderItem
                = $this->orderAddressRepository->create($orderHeader);

            $

            $orderItems->add($item);
        }
    }