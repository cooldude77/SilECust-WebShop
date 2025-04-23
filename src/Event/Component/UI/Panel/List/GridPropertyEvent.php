<?php

namespace Silecust\WebShop\Event\Component\UI\Panel\List;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Set when order list event is called, here you can set list properties dynamically
 *
 */
class GridPropertyEvent extends Event
{
    public const string EVENT_NAME = 'list_grid.property';
    private ?array $data;
    private Request $request;


    public function __construct(Request $request,array $data =null)
    {
        $this->data = $data;
        $this->request = $request;
    }

    /** @var array  */
    private array $listGridProperties = array();

    public function getListGridProperties(): array
    {
        return $this->listGridProperties;
    }

    public function setListGridProperties(array $listGridProperties): void
    {
        $this->listGridProperties = $listGridProperties;
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