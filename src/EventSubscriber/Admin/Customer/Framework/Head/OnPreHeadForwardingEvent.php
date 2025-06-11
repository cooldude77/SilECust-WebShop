<?php
/** @noinspection PhpUnused */

namespace Silecust\WebShop\EventSubscriber\Admin\Customer\Framework\Head;

use Silecust\WebShop\Event\Admin\Customer\Framework\Head\PreHeadForwardingEvent;
use Silecust\WebShop\Service\Admin\Customer\Framework\Head\PageTitleProvider;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class OnPreHeadForwardingEvent implements EventSubscriberInterface
{
    public function __construct(private readonly PageTitleProvider $pageTitleProvider)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [PreHeadForwardingEvent::EVENT_NAME => 'setHeadData'];

    }

    /**
     * @param PreHeadForwardingEvent $event
     *
     * @return void
     * @noinspection PhpUnused
     */
    public function setHeadData(PreHeadForwardingEvent $event): void
    {
        $event->setPageTitle($this->pageTitleProvider->getTitle($event->getRequest()));

    }
}