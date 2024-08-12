<?php

namespace App\Service\Admin\SideBar\Action;

use App\Service\Admin\SideBar\Action\Exception\FunctionNotFoundInMap;
use App\Service\Admin\SideBar\Action\Exception\TypeNotFoundInMap;

class PanelActionListMap
{
    private array $actionList;

    public function __construct(array $actionList)
    {

        $this->actionList = $actionList;
    }

    /**
     * @throws FunctionNotFoundInMap|TypeNotFoundInMap
     */
    public function getRoute(string $function,
                             string $type): string
    {
        $action = $this->getActions($function);
        if (empty($action['routes'][$type])) throw new TypeNotFoundInMap($function,
            $type);
        return $this->getActions($function)['routes'][$type];
    }

    /**
     * @throws FunctionNotFoundInMap
     */
    private function getActions(string $function): array
    {

        if (empty($this->actionList['functions'][$function])) throw new FunctionNotFoundInMap($function); else
            return $this->actionList['functions'][$function];
    }
}