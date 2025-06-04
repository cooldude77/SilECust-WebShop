<?php

namespace Silecust\WebShop\Service\Component\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

readonly class EventRouteChecker
{
    public function __construct(public RouterInterface $router)
    {
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