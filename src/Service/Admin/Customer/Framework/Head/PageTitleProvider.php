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

        return match ($route['_route']) {
            'sc_my', 'sc_my_dashboard' => 'Welcome to your dashboard',
            'sc_my_profile' => 'Your Profile',
            'sc_my_orders' => 'Your Orders',
            'sc_my_personal_info' => 'Your Personal Info',
            'sc_my_addresses' => 'Your Addresses',
            'sc_my_address_create' => 'Create new address',
            'sc_my_address_edit' => 'Edit address',
            'sc_my_address_display' => 'Display Address',
            'sc_my_order_display' => 'Order Details',
            'sc_my_order_item_display' => 'Order Item Details',
            default => 'No Title',
        };
    }
}