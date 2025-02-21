<?php

namespace Silecust\WebShop\Event\Component\UI\Twig;

use Symfony\Component\HttpFoundation\Request;

class BeforeTwigRenderInController
{

    const string BEFORE_TWIG_RENDER = 'before.controller.twig.render';
    private ?string $view = null;

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

    public function getView(): ?string
    {
        return $this->view;
    }

    public function setView(?string $view): void
    {
        $this->view = $view;
    }


}