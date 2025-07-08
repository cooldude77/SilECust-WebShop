<?php

namespace Silecust\WebShop\Service\Module\WebShop\External\Cart\Mapper;

use Doctrine\Common\Collections\ArrayCollection;
use Silecust\WebShop\Form\Module\WebShop\External\Cart\DTO\CartProductDTO;
use Silecust\WebShop\Service\Module\WebShop\External\Cart\Session\Item\CartItem;

class CartListToDTOMapper
{


    /**
     * @param array $cartArrayList
     *
     * @return ArrayCollection
     *
     * To be used with array from session objects
     */
    public function mapCartToDto(array $cartArrayList): ArrayCollection
    {
        $dtoArray = new  ArrayCollection();
        /** @var CartItem $cartObject */
        foreach ($cartArrayList as $productId => $cartObject) {

            $dto = new CartProductDTO();
            $dto->productId = $productId;
            $dto->quantity = $cartObject->quantity;
            $dtoArray->add($dto);
        }
        return $dtoArray;
    }

}