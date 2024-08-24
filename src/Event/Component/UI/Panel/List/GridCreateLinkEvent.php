<?php

namespace App\Event\Component\UI\Panel\List;

use Symfony\Contracts\EventDispatcher\Event;

class GridCreateLinkEvent extends Event
{

    const string BEFORE_GRID_CREATE_LINK = 'panel.grid.before_create_link';

    private mixed $data;
    private string $linkValue;

    public function getData(): mixed
    {
        return $this->data;
    }

    public function setData(mixed $data): void
    {
        $this->data = $data;
    }

    public function setLinkValue(string $linkValue): void
    {
        $this->linkValue = $linkValue;
    }


}