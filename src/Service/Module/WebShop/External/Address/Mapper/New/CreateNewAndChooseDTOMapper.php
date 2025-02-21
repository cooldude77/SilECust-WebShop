<?php

namespace Silecust\WebShop\Service\Module\WebShop\External\Address\Mapper\New;

use Silecust\WebShop\Form\Module\WebShop\External\Address\New\DTO\AddressCreateAndChooseDTO;
use Silecust\WebShop\Service\MasterData\Customer\Address\CustomerAddressDTOMapper;

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
