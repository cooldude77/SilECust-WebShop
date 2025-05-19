<?php

namespace Silecust\WebShop\Event\Component\UI\Panel\Display;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Set when order list event is called, here you can set list properties dynamically
 *
 */
class DisplayParamPropertyEvent extends Event
{
    public const string EVENT_NAME = 'display.param';
    private ?array $data;
    private Request $request;


    public function __construct(Request $request, array $data)
    {
        $this->data = $data;
        $this->request = $request;
    }

    /** @var array */
    private array $displayParamProperties = array();

    public function getDisplayParamProperties(): array
    {
        return $this->displayParamProperties;
    }

    public function setDisplayParamProperties(array $displayParamProperties): void
    {
        $this->displayParamProperties = $displayParamProperties;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }


}