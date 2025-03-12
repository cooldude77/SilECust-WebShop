<?php

namespace Silecust\WebShop\Event\Component\UI\Panel\Head;

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
        $this->request->attributes->set('page_title', $pageTitle);
    }


}