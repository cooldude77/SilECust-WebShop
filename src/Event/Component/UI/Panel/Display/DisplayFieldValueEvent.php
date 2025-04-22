<?php

namespace Silecust\WebShop\Event\Component\UI\Panel\Display;

use Symfony\Contracts\EventDispatcher\Event;

class DisplayFieldValueEvent extends Event
{

    const string EVENT_NAME = 'panel.display.field_value_event';

    private mixed $data;

    private string $value;

    public function getData(): mixed
    {
        return $this->data;
    }

    public function setData(mixed $data): void
    {
        $this->data = $data;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

}