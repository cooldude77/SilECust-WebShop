<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace Silecust\WebShop\Service\Admin\Customer\Framework\Head;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

readonly class PageTitleProvider
{
    public function __construct(private readonly RouterInterface $router)
    {
    }

    public function getTitle(Request $request): string
    {
        $route = $this->router->match($request->getPathInfo());

        switch ($route['_route']) {
            case 'sc_my':
                return 'Welcome to your dashboard';


        }
        return 'No Title';
    }
}