<?php /** @noinspection ALL */

namespace Silecust\WebShop\Controller\Admin\Customer\Framework\Components;

use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HeadController extends EnhancedAbstractController
{

    public function head(Request $request): Response
    {
        return $this->render(
            '@SilecustWebShop/admin/customer/ui/panel/head/head.html.twig', ['request' => $request]);


    }


}