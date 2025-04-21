<?php

namespace Silecust\WebShop\Service\Twig\Event\DispatcherFunction\Grid;

use Silecust\WebShop\Service\Twig\Event\Provider\Grid\EventProvider;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class EventFunctions extends AbstractExtension
{
    public function __construct(
        private readonly EventProvider            $gridEventProvider,
        private readonly EventDispatcherInterface $eventDispatcher)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('dispatchGridColumnEvent', [$this, 'dispatchGridColumnEvent']),
            new TwigFunction('dispatchGridCreateLinkEvent', [$this, 'dispatchGridCreateLinkEvent']),
            new TwigFunction('dispatchGridRowDataEvent', [$this, 'dispatchGridRowDataEvent']),
            new TwigFunction('dispatchGridRowHeaderEvent', [$this, 'dispatchGridRowHeaderEvent']),
            new TwigFunction('dispatchGridPaginationEvent', [$this, 'dispatchGridPaginationEvent']),
        ];
    }

    public function dispatchGridColumnEvent(mixed $data): mixed
    {
        $event = $this->gridEventProvider->provideGridColumnEvent();
        $event->setData($data);
        return $this->eventDispatcher->dispatch($event, $event::EVENT_NAME);
    }

    public function dispatchGridCreateLinkEvent(mixed $data): mixed
    {
        $event = $this->gridEventProvider->provideGridCreateLinkEvent();
        $event->setData($data);
        return $this->eventDispatcher->dispatch($event, $event::EVENT_NAME);
    }

    public function dispatchGridRowDataEvent(mixed $data): mixed
    {
        $event = $this->gridEventProvider->provideGridRowDataEvent();
        $event->setData($data);
        return $this->eventDispatcher->dispatch($event, $event::EVENT_NAME);
    }

    public function dispatchGridRowHeaderEvent(mixed $data): mixed
    {
        $event = $this->gridEventProvider->provideGridRowHeaderEvent();
        $event->setData($data);
        return $this->eventDispatcher->dispatch($event, $event::EVENT_NAME);
    }

    public function dispatchGridPaginationEvent(mixed $data): mixed
    {
        $event = $this->gridEventProvider->provideGridPaginationEvent();
        $event->setData($data);
        return $this->eventDispatcher->dispatch($event, $event::EVENT_NAME);
    }


}