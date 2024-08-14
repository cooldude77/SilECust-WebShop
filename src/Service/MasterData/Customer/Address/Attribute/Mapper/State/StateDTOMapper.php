<?php

namespace App\Service\MasterData\Customer\Address\Attribute\Mapper\State;

use App\Entity\State;
use App\Form\MasterData\Customer\Address\Attribute\State\DTO\StateDTO;

class StateDTOMapper
{
    public function mapToEntityForCreate(StateDTO $stateDTO): State
    {
        $state = new State();
        return $state;
    }

    public function mapToEntityForEdit(StateDTO $stateDTO): State
    {
        $state = new State();
        return $state;
    }
}