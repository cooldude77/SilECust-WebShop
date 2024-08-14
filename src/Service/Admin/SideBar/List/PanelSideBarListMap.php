<?php

namespace App\Service\Admin\SideBar\List;

class PanelSideBarListMap
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