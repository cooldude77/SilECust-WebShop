<?php

namespace App\Service\Module\WebShop\External\Address\Mapper\New;

use App\Form\Module\WebShop\External\Address\New\DTO\AddressCreateAndChooseDTO;
use App\Service\MasterData\Customer\Address\CustomerAddressDTOMapper;

class CreateNewAndChooseDTOMapper
{

    public function __construct(private CustomerAddressDTOMapper $customerAddressDTOMapper)
    {
    }

    public function map(AddressCreateAndChooseDTO $addressCreateAndChooseDTO)
    {

        return $this->customerAddressDTOMapper->mapDtoToEntityForCreate
        (
            $addressCreateAndChooseDTO->address
        );
    }

    public function isChosen(AddressCreateAndChooseDTO $addressCreateAndChooseDTO): bool
    {
        return $addressCreateAndChooseDTO->isChosen;
    }
}
