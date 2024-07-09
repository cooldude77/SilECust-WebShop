<?php

namespace App\Service\Admin\Employee\FrameWork;

class AdminRouteObject
{

    private string $function;
    private string $type;
    private string $controllerAction;
    private array $params;
    private string $routeName;

    public function getFunction(): string
    {
        return $this->function;
    }

    public function setFunction(string $function): void
    {
        $this->function = $function;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }


    public function getControllerAction(): string
    {
        return $this->controllerAction;
    }

    public function setControllerAction(string $controllerAction): void
    {
        $this->controllerAction = $controllerAction;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    public function getRouteName(): string
    {
        return $this->routeName;
    }

    public function setRouteName(string $routeName): void
    {
        $this->routeName = $routeName;
    }



}