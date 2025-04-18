<?php

namespace Silecust\WebShop\Event\Component\UI\Panel\List;

use Symfony\Contracts\EventDispatcher\Event;

class GridPaginationEvent extends Event
{

    const string EVENT_NAME = 'panel.grid.pagination';

    private mixed $data;

    public function getData(): mixed
    {
        return $this->data;
    }

    public function setData(mixed $data): void
    {
        $this->data = $data;
    }


}