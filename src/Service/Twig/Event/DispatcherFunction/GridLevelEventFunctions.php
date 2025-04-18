<?php

namespace Silecust\WebShop\Service\Twig\Event\DispatcherFunction;

use Silecust\WebShop\Service\Twig\Event\Provider\GridEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class GridLevelEventFunctions extends AbstractExtension
{
    public function __construct(
        private readonly GridEvent                $gridEventProvider,
        private readonly EventDispatcherInterface $eventDispatcher)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('dispatchGridColumnEvent', [$this, 'dispatchGridEvent']),
            new TwigFunction('dispatchGridCreateLinkEvent', [$this, 'dispatchGridEvent']),
            new TwigFunction('dispatchGridRowDataEvent', [$this, 'dispatchGridEvent']),
            new TwigFunction('dispatchGridRowHeaderEvent', [$this, 'dispatchGridEvent']),
            new TwigFunction('dispatchGridPaginationEvent', [$this, 'dispatchGridEvent']),
        ];
    }

    public function dispatchGridEvent(string $eventName, mixed $data): mixed
    {
        $event = $this->gridEventProvider->provideGridColumnEvent();
        $event->setData($data);
        return $this->eventDispatcher->dispatch($event, $eventName);
    }
}