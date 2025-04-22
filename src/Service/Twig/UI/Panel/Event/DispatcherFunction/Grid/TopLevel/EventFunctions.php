<?php

namespace Silecust\WebShop\Service\Twig\UI\Panel\Event\DispatcherFunction\Grid\TopLevel;

use Silecust\WebShop\Service\Twig\UI\Panel\Event\Provider\Grid\TopLevel\EventProvider;
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


}