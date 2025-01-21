<?php

namespace App\Event\Admin\Employee\FrameWork;

use App\Service\Component\UI\Panel\Components\PanelHeadController;
use App\Service\Component\UI\Panel\Components\PanelHeaderController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\EventDispatcher\Event;

class PreHeadForwardingEvent extends Event
{
    const string PRE_HEAD_FORWARDING_EVENT = 'admin_panel.before_forwarding';


    /**
     * @param Request          $request
     * @param SessionInterface $session
     */
    public function __construct(private readonly Request $request, private SessionInterface $session)
    {
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function setPageTitle(string $pageTitle): void
    {
        $this->session->set(PanelHeadController::PAGE_TITLE, $pageTitle);
    }


}