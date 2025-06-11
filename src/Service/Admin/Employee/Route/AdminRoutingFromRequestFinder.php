<?php

namespace Silecust\WebShop\Service\Admin\Employee\Route;

use Silecust\WebShop\Exception\Admin\Employee\FrameWork\AdminUrlFunctionKeyParameterNull;
use Silecust\WebShop\Exception\Admin\Employee\FrameWork\AdminUrlTypeKeyParameterNull;
use Silecust\WebShop\Exception\Admin\SideBar\Action\EmptyActionListMapException;
use Silecust\WebShop\Exception\Admin\SideBar\Action\FunctionNotFoundInMap;
use Silecust\WebShop\Exception\Admin\SideBar\Action\TypeNotFoundInMap;
use Silecust\WebShop\Service\Admin\SideBar\Action\PanelActionListMapBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\RouterInterface;

readonly class AdminRoutingFromRequestFinder
{
    public function __construct(private PanelActionListMapBuilder $builder,
        private RouterInterface $router
    ) {
    }

    /**
     * @param Request $request
     *
     * @return AdminRouteObject
     * @throws AdminUrlFunctionKeyParameterNull
     * @throws AdminUrlTypeKeyParameterNull
     * @throws EmptyActionListMapException
     * @throws FunctionNotFoundInMap
     * @throws TypeNotFoundInMap
     */
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
        $id = 0;
        if (!empty($request->get('id'))) {
            $params['id'] = $request->get('id');
            $id = $request->get('id');
        }


        $adminRouteObject->setFunction($function);
        $adminRouteObject->setType($type);
        $adminRouteObject->setRouteName($routeName);
        $adminRouteObject->setControllerAction($controllerAction);
        $adminRouteObject->setParams($params);
        $adminRouteObject->setId($id);

        return $adminRouteObject;

    }

}