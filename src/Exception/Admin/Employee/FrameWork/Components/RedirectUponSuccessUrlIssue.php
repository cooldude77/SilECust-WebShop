<?php

namespace Silecust\WebShop\Exception\Admin\Employee\FrameWork\Components;

use Exception;
use Symfony\Component\HttpFoundation\Request;

class RedirectUponSuccessUrlIssue extends Exception
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function __construct(private readonly Request $request)
    {
        parent::__construct("The redirect route is absent");
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

}