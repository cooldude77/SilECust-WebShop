<?php

namespace Silecust\WebShop\Service\Admin\SideBar\List;
use Symfony\Component\DependencyInjection\Attribute\Exclude;

#[Exclude]
class SideBarListMap
{
    private array $sideBarList;

    public function __construct(array $sideBarList)
    {

        $this->sideBarList = $sideBarList;
    }

    public function getSideBarList(): array
    {
        return $this->sideBarList;
    }
}