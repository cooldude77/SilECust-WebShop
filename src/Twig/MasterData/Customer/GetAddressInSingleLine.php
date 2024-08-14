<?php

namespace App\Twig\MasterData\Customer;

use App\Service\MasterData\Customer\Address\CustomerAddressQuery;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class GetAddressInSingleLine extends AbstractExtension
{
    public function __construct(private  readonly  CustomerAddressQuery $customerAddressQuery)
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('singleLine', [$this, 'getSingleLine']),
        ];
    }

    public function getSingleLine($id): string
    {
        return  $this->customerAddressQuery->getAddressInASingleLine($id);
    }
}