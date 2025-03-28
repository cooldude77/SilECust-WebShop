<?php

namespace Silecust\WebShop\Service\Twig\EventDispatcher;

use Silecust\WebShop\Event\Component\UI\Panel\List\GridColumnEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class GridColumnEventDispatcher extends AbstractExtension
{
    public function __construct(private readonly EventDispatcherInterface $eventDispatcher)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('dispatchGridColumnEventEvent', [$this, 'dispatchGridColumnEventEvent']),
        ];
    }

    public function dispatchGridColumnEventEvent(string $eventName, mixed $data): mixed
    {
        $event = new GridColumnEvent();
        $event->setData($data);
        return $this->eventDispatcher->dispatch($event, $eventName);
    }
}