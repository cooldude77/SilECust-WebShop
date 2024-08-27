<?php

namespace App\Service\MasterData\Customer\Address\Attribute\Mapper\PostalCode;

use App\Entity\PostalCode;
use App\Form\MasterData\Customer\Address\Attribute\PostalCode\DTO\PostalCodeDTO;

class PostalCodeDTOMapper
{
    public function mapToEntityForCreate(PostalCodeDTO $postalCodeDTO): PostalCode
    {
        $postalCode = new PostalCode();
        return $postalCode;
    }

    public function mapToEntityForEdit(PostalCodeDTO $postalCodeDTO): PostalCode
    {
        $postalCode = new PostalCode();
        return $postalCode;
    }
}