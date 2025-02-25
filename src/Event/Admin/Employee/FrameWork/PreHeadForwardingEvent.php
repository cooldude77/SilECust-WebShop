<?php

namespace Silecust\WebShop\Event\Admin\Employee\FrameWork;

use Silecust\WebShop\Service\Component\UI\Panel\Components\PanelHeadController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

class PreHeadForwardingEvent extends Event
{
    const string PRE_HEAD_FORWARDING_EVENT = 'admin_panel.before_forwarding';

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