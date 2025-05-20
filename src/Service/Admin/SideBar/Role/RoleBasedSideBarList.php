<?php

namespace Silecust\WebShop\Service\Admin\SideBar\Role;

use Silecust\WebShop\Service\Admin\SideBar\List\ListMapBuilder;
use Symfony\Bundle\SecurityBundle\Security;

readonly class RoleBasedSideBarList
{

    public function __construct(private ListMapBuilder $listMapBuilder,
        private readonly Security                      $security
    ) {
    }

    public function getListBasedOnRole($contextUrl): array
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