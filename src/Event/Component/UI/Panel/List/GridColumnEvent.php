<?php

namespace Silecust\WebShop\Event\Component\UI\Panel\List;

use Symfony\Contracts\EventDispatcher\Event;

class GridColumnEvent extends Event
{

    private bool $dataChanged = false;
    const string EVENT_NAME = 'panel.grid.before_column_display';

    private mixed $data;

    private string $template;

    public function getData(): mixed
    {
        return $this->data;
    }

    public function setData(mixed $data): void
    {
        $this->data = $data;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }

}