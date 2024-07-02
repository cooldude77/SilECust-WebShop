<?php

namespace App\Service\Module\WebShop\External\Cart\Order\Mapper;

use App\Entity\Customer;
use App\Form\Module\WebShop\External\Order\DTO\Components\Components\OrderItemDTO;
use App\Service\Module\WebShop\External\Cart\Session\CartSessionProductService;
use App\Service\Module\WebShop\External\Cart\Session\Object\CartSessionObject;

class CartToOrderObjectDTOMapper
{

    public function __construct(private readonly CartSessionProductService $cartService,
    ) {
    }

    public function map(): array
    {

        $orderItemDTOArray = [];
        /**
         * @var  int              $productId
         * @var CartSessionObject $cartObject
         */
        foreach ($this->cartService->getCartArray() as $productId => $cartObject) {

            $orderItemDTO = new OrderItemDTO();

            $orderItemDTO->productId = $productId;
            $orderItemDTO->quantity = $cartObject->quantity;


            $orderItemDTOArray[] = $orderItemDTO;
        }

        return $orderItemDTOArray;
    }

}