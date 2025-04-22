<?php

namespace Silecust\WebShop\Event\Component\UI\Panel\List\TopLevel;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

class DisplayParametersEvent extends Event
{

    public const string GET_DISPLAY_PROPERTY_EVENT = 'panel.display.display_property' ;
    private array $parameterList = array();

    public function __construct(private readonly Request $request, private ?array $data =null)
    {
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    /**
     * @param array $parameterList
     * @return void
     */
    public function setParameterList(array $parameterList): void
    {
        $this->parameterList = $parameterList;
    }

    public function getParameterList(): array
    {
        return $this->parameterList;
    }


}