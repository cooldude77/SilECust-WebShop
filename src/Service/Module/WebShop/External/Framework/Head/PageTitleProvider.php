<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace Silecust\WebShop\Service\Module\WebShop\External\Framework\Head;

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
            'sc_home' => 'Home Page',
            'sc_web_shop_checkout_addresses ' => 'Addresses',
            'sc_web_shop_checkout_address_create' => 'Create Address',
            'sc_web_shop_checkout_choose_address_from_list' => 'Choose Address',
            'sc_web_shop_checkout' => 'Checkout',
            'sc_web_shop_view_order' => 'View Order',
            'sc_web_shop_payment_start' => 'Payment Start',
            'sc_web_shop_payment_success' => 'Payment Success',
            'sc_web_shop_product_single_display' => 'Product',
            'sc_web_shop_sign_up_or_login' => 'Sign Up Or Login',
            default => 'No Title',
        };
    }
}