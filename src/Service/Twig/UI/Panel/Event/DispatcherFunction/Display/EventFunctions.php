<?php

namespace Silecust\WebShop\Service\Twig\UI\Panel\Event\DispatcherFunction\Display;

use Silecust\WebShop\Service\Twig\UI\Panel\Event\Provider\Display\EventProvider;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class EventFunctions extends AbstractExtension
{
    public function __construct(
        private readonly EventProvider            $eventProvider,
        private readonly EventDispatcherInterface $eventDispatcher)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('dispatchDisplayEditLinkEvent', [$this, 'dispatchDisplayEditLinkEvent']),
            new TwigFunction('dispatchFieldValueEvent', [$this, 'dispatchFieldValueEvent']),
        ];
    }


    public function dispatchDisplayEditLinkEvent(mixed $data): mixed
    {
        $event = $this->eventProvider->provideDisplayEditLinkEvent();
        $event->setData($data);
        return $this->eventDispatcher->dispatch($event, $event::EVENT_NAME);
    }

    public function dispatchFieldValueEvent(mixed $data): mixed
    {
        $event = $this->eventProvider->provideFieldValueEvent();
        $event->setData($data);
        return $this->eventDispatcher->dispatch($event, $event::EVENT_NAME);
    }


}