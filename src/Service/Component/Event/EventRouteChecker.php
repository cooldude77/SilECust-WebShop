<?php

namespace Silecust\WebShop\Service\Component\Event;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

readonly class EventRouteChecker
{
    public function __construct(public RouterInterface  $router)
    {
    }

    public function checkFunctions(Request $request, array $allowedFunction): bool
    {
        return in_array($request->query->get('_function'), $allowedFunction);

    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $directRoute
     * @param string $function
     * @param string $type
     * @return bool
     */
    public function checkIfAdminOrDirectInvocationTrue(Request $request, string $directRoute, string $function, string $type): bool
    {
        $bool = false;
        if ($this->isAdminRoute($request)) {
            if ($this->hasFunction($request, $function))
                if ($this->hasType($request, $type))
                    $bool = true;
        } else if ($this->isInRouteList($request, array($directRoute)))
            $bool = true;

        return $bool;
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