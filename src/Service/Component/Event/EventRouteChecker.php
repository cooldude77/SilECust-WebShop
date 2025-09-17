<?php

namespace Silecust\WebShop\Service\Component\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

readonly class EventRouteChecker
{
    public function __construct(public RouterInterface $router)
    {
    }

    public function isAdminRoute(Request $request): bool
    {
        return $this->isInRouteList($request, ['sc_admin_panel']);
    }

    public function isInRouteList(Request $request, array $routes): bool
    {
        $route = $this->router->match($request->getPathInfo());

        return in_array($route['_route'], $routes);
    }

    public function checkFunctions(Request $request, array $allowedFunction): bool
    {
        return in_array($request->query->get('_function'), $allowedFunction);

    }

    public function checkIfTrue(Request $request,array $routeListArray, string $function, string $type): bool
    {
        return $this->isInRouteList($request, ['sc_admin_panel', 'sc_admin_category_file_image_list'])
            &&
            $this->hasFunction($request, 'category_file_image')
            &&
            $this->hasType($request, 'list');
    }

    public function hasFunction(Request $request, string $functionName): bool
    {

        return $request->query->get('_function') == $functionName;

    }

    public function hasType(Request $request, string $type): bool
    {
        // order item list is never shown standalone
        return $request->query->get('_type') == $type;
    }
}