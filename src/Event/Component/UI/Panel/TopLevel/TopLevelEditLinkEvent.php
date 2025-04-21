<?php

namespace Silecust\WebShop\Event\Component\UI\Panel\TopLevel;

use Symfony\Contracts\EventDispatcher\Event;

class TopLevelEditLinkEvent extends Event
{

    const string EVENT_NAME = 'panel.display.on_edit_link';

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