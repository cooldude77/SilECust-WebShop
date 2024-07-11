<?php

namespace App\Exception\Admin\Employee\Common;

use App\Service\Admin\Employee\FrameWork\AdminRouteObject;
use Exception;

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