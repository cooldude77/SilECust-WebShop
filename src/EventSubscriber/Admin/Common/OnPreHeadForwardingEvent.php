<?php
/** @noinspection PhpUnused */

namespace Silecust\WebShop\EventSubscriber\Admin\Common;

use Silecust\WebShop\Event\Admin\Employee\FrameWork\PreHeadForwardingEvent;
use Silecust\WebShop\Exception\Admin\Common\FunctionNotMappedToAnyEntity;
use Silecust\WebShop\Exception\Admin\Employee\Common\TitleNotFoundForAdminRouteObject;
use Silecust\WebShop\Exception\Admin\Employee\FrameWork\AdminUrlFunctionKeyParameterNull;
use Silecust\WebShop\Exception\Admin\Employee\FrameWork\AdminUrlTypeKeyParameterNull;
use Silecust\WebShop\Exception\Admin\SideBar\Action\EmptyActionListMapException;
use Silecust\WebShop\Exception\Admin\SideBar\Action\FunctionNotFoundInMap;
use Silecust\WebShop\Exception\Admin\SideBar\Action\TypeNotFoundInMap;
use Silecust\WebShop\Service\Admin\Employee\Common\AdminTitle;
use Silecust\WebShop\Service\Admin\Employee\FrameWork\AdminRoutingFromRequestFinder;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class OnPreHeadForwardingEvent implements EventSubscriberInterface
{
    public function __construct(private AdminRoutingFromRequestFinder $adminRoutingFromRequestFinder,
        private AdminTitle $adminTitle,
        private readonly LoggerInterface $logger
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [PreHeadForwardingEvent::PRE_HEAD_FORWARDING_EVENT => 'setHeadData'];

    }

    /**
     * @param PreHeadForwardingEvent $event
     *
     * @return void
     * @noinspection PhpUnused
     */
    public function setHeadData(PreHeadForwardingEvent $event): void
    {
        try {
            $object = $this->adminRoutingFromRequestFinder->getAdminRouteObject(
                $event->getRequest()
            );
            // should handle function and type relevant to it
            $event->setPageTitle(
                $this->adminTitle->getTitle($object)
            );

        } catch (
        EmptyActionListMapException
        |FunctionNotFoundInMap
        |TypeNotFoundInMap
        |AdminUrlFunctionKeyParameterNull
        |AdminUrlTypeKeyParameterNull
        |FunctionNotMappedToAnyEntity
        |TitleNotFoundForAdminRouteObject $e) {
            // do nothing
            $this->logger->warning($e);
        }
    }
}