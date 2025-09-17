<?php

namespace Silecust\WebShop\Service\Admin\SideBar\Action;

use Silecust\WebShop\Exception\Admin\SideBar\Action\FunctionNotFoundInMap;
use Silecust\WebShop\Exception\Admin\SideBar\Action\TypeNotFoundInMap;

class ListMap
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