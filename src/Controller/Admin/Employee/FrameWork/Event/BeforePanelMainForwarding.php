<?php

namespace App\Controller\Admin\Employee\FrameWork\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

class BeforePanelMainForwarding extends Event
{

    public const string BEFORE_PANEL_MAIN_GET_RESPONSE_EVENT = 'BEFORE_PANEL_MAIN_GET_RESPONSE_EVENT';


    public function __construct(private readonly Request $request)
    {
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

}