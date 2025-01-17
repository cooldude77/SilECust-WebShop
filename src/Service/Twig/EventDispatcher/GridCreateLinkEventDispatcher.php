<?php

namespace App\Service\Twig\EventDispatcher;

use App\Event\Component\UI\Panel\List\GridCreateLinkEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class GridCreateLinkEventDispatcher extends AbstractExtension
{
    public function __construct(private readonly EventDispatcherInterface $eventDispatcher)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('dispatchGridCreateLinkEvent', [$this, 'dispatchGridCreateLinkEvent']),
        ];
    }

    public function dispatchGridCreateLinkEvent(string $eventName, mixed $data): mixed
    {
        $event = new GridCreateLinkEvent();
        $event->setData($data);
        return $this->eventDispatcher->dispatch($event, $eventName);
    }
}