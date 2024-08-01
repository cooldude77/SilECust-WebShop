<?php

namespace App\Twig\EventDispatcher;

use App\Event\Component\UI\TwigGridColumnEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigGridColumnEventDispatcher extends AbstractExtension
{
    public function __construct(private readonly EventDispatcherInterface $eventDispatcher)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('dispatchEvent', [$this, 'dispatch']),
        ];
    }

    public function dispatch(string $eventName, mixed $data): mixed
    {
        $event = new TwigGridColumnEvent();
        $event->setData($data);
        return $this->eventDispatcher->dispatch($event, $eventName);
    }
}