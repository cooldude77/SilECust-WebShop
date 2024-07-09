<?php

namespace App\Service\Admin\Employee\FrameWork;

use App\Exception\Admin\Employee\FrameWork\AdminUrlFunctionKeyParameterNull;
use App\Exception\Admin\Employee\FrameWork\AdminUrlTypeKeyParameterNull;
use App\Service\Admin\Action\PanelActionListMapBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\RouterInterface;

class AdminRoutingFromRequestFinder
{
    public function __construct(private readonly PanelActionListMapBuilder $builder,
        private readonly RouterInterface $router
    ) {
    }

    public function getAdminRouteObject(Request $request): AdminRouteObject
    {

        $adminRouteObject = new AdminRouteObject();

        if ($request->query->get('_function') == null) {
            throw new AdminUrlFunctionKeyParameterNull();
        }
        if ($request->query->get('_type') == null) {
            throw new AdminUrlTypeKeyParameterNull();
        }


        $function = $request->query->get('_function');

        $type = $request->query->get('_type');


        $routeName = $this->builder->build()->getPanelActionListMap()->getRoute(
            $function, $type
        );

        // call controller
        $callRoute = $this->router->getRouteCollection()->get($routeName);

        if ($callRoute == null) {
            throw  new RouteNotFoundException($routeName);
        }

        $controllerAction = $callRoute->getDefault('_controller');

        $params = ['request' => $request];
        if (!empty($request->get('id'))) {
            $params['id'] = $request->get('id');
        }


        $adminRouteObject->setFunction($function);
        $adminRouteObject->setType($type);
        $adminRouteObject->setRouteName($routeName);
        $adminRouteObject->setControllerAction($controllerAction);
        $adminRouteObject->setParams($params);

        return $adminRouteObject;

    }

}