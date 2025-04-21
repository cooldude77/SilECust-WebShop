<?php

namespace Silecust\WebShop\Service\Twig\Event\DispatcherFunction\TopLevel;

use Silecust\WebShop\Service\Twig\Event\Provider\TopLevel\EventProvider;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class EventFunctions extends AbstractExtension
{
    public function __construct(
        private readonly EventProvider            $topLevelLinkEventProvider,
        private readonly EventDispatcherInterface $eventDispatcher)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('dispatchTopLevelEditLinkEvent', [$this, 'dispatchTopLevelEditLinkEvent']),
            new TwigFunction('dispatchDisplayLinkEvent', [$this, 'dispatchDisplayLinkEvent']),
             ];
    }

    public function dispatchTopLevelEditLinkEvent(mixed $data): mixed
    {
        $event = $this->topLevelLinkEventProvider->provideTopLevelEditLinkEvent();
        $event->setData($data);
        return $this->eventDispatcher->dispatch($event, $event::EVENT_NAME);
    }

    public function dispatchDisplayLinkEvent(mixed $data): mixed
    {
        $event = $this->topLevelLinkEventProvider->provideDisplayLinkEvent();
        $event->setData($data);
        return $this->eventDispatcher->dispatch($event, $event::EVENT_NAME);
    }


}