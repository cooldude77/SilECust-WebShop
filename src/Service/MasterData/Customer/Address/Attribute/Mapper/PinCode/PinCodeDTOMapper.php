<?php

namespace App\Service\MasterData\Customer\Address\Attribute\Mapper\PinCode;

use App\Entity\PinCode;
use App\Form\MasterData\Customer\Address\Attribute\PinCode\DTO\PinCodeDTO;

class PinCodeDTOMapper
{
    public function mapToEntityForCreate(PinCodeDTO $pinCodeDTO): PinCode
    {
        $pinCode = new PinCode();
        return $pinCode;
    }

    public function mapToEntityForEdit(PinCodeDTO $pinCodeDTO): PinCode
    {
        $pinCode = new PinCode();
        return $pinCode;
    }
}