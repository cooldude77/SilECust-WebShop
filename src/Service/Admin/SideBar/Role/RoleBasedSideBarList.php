<?php

namespace App\Service\Admin\SideBar\Role;

use App\Service\Admin\SideBar\List\PanelSideBarListMapBuilder;

class RoleBasedSideBarList
{

    public function __construct(private readonly PanelSideBarListMapBuilder $listMapBuilder)
    {
    }

    public function getListBasedOnRole(string $role, $contextUrl): array
    {

        $list = $this->listMapBuilder->build($contextUrl)->getSideBarList();

        $finalList = ['sections' => []];
        foreach ($list['sections'] as $section) {
            if (in_array($role, $section['roles'])) {
                $finalList['sections'][] = $section;
            }

        }

        return $finalList;

    }



}