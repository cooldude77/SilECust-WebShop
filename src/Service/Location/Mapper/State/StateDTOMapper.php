<?php

namespace Silecust\WebShop\Service\Location\Mapper\State;

use Silecust\WebShop\Entity\State;
use Silecust\WebShop\Form\MasterData\Customer\Address\Attribute\State\DTO\StateDTO;
use Silecust\WebShop\Repository\CountryRepository;
use Silecust\WebShop\Repository\StateRepository;

class StateDTOMapper
{
    public function __construct(private readonly StateRepository $stateRepository,
    private readonly CountryRepository $countryRepository)
    {
    }

    public function mapToEntityForCreate(StateDTO $stateDTO): State
    {
        $state = $this->stateRepository->create($this->countryRepository->find($stateDTO->countryId));

        $state->setCode($stateDTO->code);
        $state->setName($stateDTO->name);

        return $state;
    }

    public function mapToEntityForEdit(StateDTO $stateDTO): State
    {
        $state = $this->stateRepository->find($stateDTO->id);

        $state->setId($stateDTO->id);
        $state->setCode($stateDTO->code);
        $state->setName($stateDTO->name);

        $state->setCountry($this->countryRepository->find($stateDTO->countryId));

        return $state;
    }

    public function mapToDTOForEdit(State $state): StateDTO
    {
        $stateDTO = new StateDTO();
        $stateDTO->id = $state->getId();
        $stateDTO->name = $state->getName();
        $stateDTO->code = $state->getCode();
        $stateDTO->countryId = $state->getCountry()->getId();

        return $stateDTO;
    }
}