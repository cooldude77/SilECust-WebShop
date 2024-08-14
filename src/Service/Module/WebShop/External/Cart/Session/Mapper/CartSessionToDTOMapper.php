<?php

namespace App\Service\Module\WebShop\External\Cart\Session\Mapper;

use App\Form\Module\WebShop\External\Cart\DTO\CartProductDTO;
use App\Service\Module\WebShop\External\Cart\Session\Object\CartSessionObject;
use Doctrine\Common\Collections\ArrayCollection;

class CartSessionToDTOMapper
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
        /** @var CartSessionObject $cartObject */
        foreach ($cartArrayList as $productId => $cartObject) {

            $dto = new CartProductDTO();
            $dto->productId = $productId;
            $dto->quantity = $cartObject->quantity;
            $dtoArray->add($dto);
        }
        return $dtoArray;
    }

}