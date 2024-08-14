<?php

namespace App\Service\Module\WebShop\External\Address\Mapper\Existing;

use App\Entity\CustomerAddress;
use App\Form\Module\WebShop\External\Address\Existing\DTO\AddressChooseExistingMultipleDTO;
use App\Form\Module\WebShop\External\Address\Existing\DTO\AddressChooseExistingSingleDTO;

readonly class ChooseFromMultipleAddressDTOMapper
{


    public function mapAddressesToDto(array $addresses, array $array):
    AddressChooseExistingMultipleDTO {

        $multi = new AddressChooseExistingMultipleDTO();

        $incomingArray = $array['address_choose_existing_multiple_form']['addresses'] ?? array();
        /** @var CustomerAddress $address */
        foreach ($addresses as $key => $address) {
            $dto = new AddressChooseExistingSingleDTO();

            $dto->id = $address->getId();
            // todo: validation , two entries cannot be chosen
            $dto->isChosen = $incomingArray[$key]['isChosen'] ?? false;

            $multi->add($dto);


        }
        return $multi;
    }
}