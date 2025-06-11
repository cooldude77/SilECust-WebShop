<?php /** @noinspection ALL */

namespace Silecust\WebShop\Event\Module\WebShop\External\Framework\Head;

use Silecust\Framework\Service\Twig\TwigConstants;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

class PreHeadForwardingEvent extends Event
{
    const string EVENT_NAME = 'module.web_shop.header.before_forwarding';

    /**
     * @param Request $request
     */
    public function __construct(private readonly Request $request)
    {
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function setPageTitle(string $pageTitle): void
    {
        $this->request->attributes->set(TwigConstants::UI_WEB_PAGE_TITLE, $pageTitle);
    }


}