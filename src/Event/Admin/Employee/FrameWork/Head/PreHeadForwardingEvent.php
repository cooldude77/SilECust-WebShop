<?php

namespace Silecust\WebShop\Event\Admin\Employee\FrameWork\Head;

use Silecust\WebShop\Service\Component\UI\Panel\Components\PanelHeadController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

class PreHeadForwardingEvent extends Event
{
    const string EVENT_NAME = 'admin_panel.employee.header.before_forwarding';

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
        $this->request->getSession()->set(PanelHeadController::PAGE_TITLE, $pageTitle);
    }


}