<?php

namespace App\Service\Admin\SideBar\Role;

use App\Service\Admin\SideBar\List\PanelSideBarListMapBuilder;
use Symfony\Bundle\SecurityBundle\Security;

readonly class RoleBasedSideBarList
{

    public function __construct(private PanelSideBarListMapBuilder $listMapBuilder,
        private readonly Security $security
    ) {
    }

    public function getListBasedOnRole( $contextUrl): array
    {

        $list = $this->listMapBuilder->build($contextUrl)->getSideBarList();

        $finalList = ['sections' => []];
        foreach ($list['sections'] as $section) {
            if ($this->security->isGranted($section['roles'][0])) {
                $finalList['sections'][] = $section;
            }

        }

        return $finalList;

    }


}