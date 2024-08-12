<?php
/** @noinspection PhpUnused */

namespace App\EventSubscriber\Admin\Common;

use App\Event\Admin\Employee\FrameWork\PreHeadForwardingEvent;
use App\Exception\Admin\Common\FunctionNotMappedToAnyEntity;
use App\Exception\Admin\Employee\Common\TitleNotFoundForAdminRouteObject;
use App\Exception\Admin\Employee\FrameWork\AdminUrlFunctionKeyParameterNull;
use App\Exception\Admin\Employee\FrameWork\AdminUrlTypeKeyParameterNull;
use App\Service\Admin\Employee\Common\AdminTitle;
use App\Service\Admin\Employee\FrameWork\AdminRoutingFromRequestFinder;
use App\Service\Admin\SideBar\Action\Exception\EmptyActionListMapException;
use App\Service\Admin\SideBar\Action\Exception\FunctionNotFoundInMap;
use App\Service\Admin\SideBar\Action\Exception\TypeNotFoundInMap;
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