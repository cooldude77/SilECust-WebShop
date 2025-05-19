<?php

namespace Silecust\WebShop\Event\Component\UI\Panel\Display;

use Symfony\Contracts\EventDispatcher\Event;

class DisplayEditLinkEvent extends Event
{

    const string EVENT_NAME = 'panel.display.edit_link';

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