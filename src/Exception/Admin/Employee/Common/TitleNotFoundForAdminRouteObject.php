<?php

namespace Silecust\WebShop\Exception\Admin\Employee\Common;

use Exception;
use Silecust\WebShop\Service\Admin\Employee\Route\AdminRouteObject;

class TitleNotFoundForAdminRouteObject extends Exception
{

    private AdminRouteObject $adminRouteObject;

    /**
     * @param AdminRouteObject $adminRouteObject
     *
     * @return void
     */
    public function setAdminRouteObject(AdminRouteObject $adminRouteObject
    ): void {
        $this->adminRouteObject = $adminRouteObject;
    }

    public function getAdminRouteObject(): AdminRouteObject
    {
        return $this->adminRouteObject;
    }

}