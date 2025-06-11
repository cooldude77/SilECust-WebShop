<?php
/** @noinspection PhpUnused */

namespace Silecust\WebShop\EventSubscriber\Admin\Employee\Framework\Head;

use Psr\Log\LoggerInterface;
use Silecust\Framework\Service\Twig\TwigConstants;
use Silecust\WebShop\Event\Admin\Employee\FrameWork\Head\PreHeadForwardingEvent;
use Silecust\WebShop\Exception\Admin\Common\FunctionNotMappedToAnyEntity;
use Silecust\WebShop\Exception\Admin\Employee\Common\TitleNotFoundForAdminRouteObject;
use Silecust\WebShop\Exception\Admin\Employee\FrameWork\AdminUrlFunctionKeyParameterNull;
use Silecust\WebShop\Exception\Admin\Employee\FrameWork\AdminUrlTypeKeyParameterNull;
use Silecust\WebShop\Exception\Admin\SideBar\Action\EmptyActionListMapException;
use Silecust\WebShop\Exception\Admin\SideBar\Action\FunctionNotFoundInMap;
use Silecust\WebShop\Exception\Admin\SideBar\Action\TypeNotFoundInMap;
use Silecust\WebShop\Service\Admin\Employee\FrameWork\Head\PageTitleProvider;
use Silecust\WebShop\Service\Admin\Employee\Route\AdminRoutingFromRequestFinder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class OnPreHeadForwardingEvent implements EventSubscriberInterface
{
    public function __construct(private AdminRoutingFromRequestFinder $adminRoutingFromRequestFinder,
        private PageTitleProvider                                     $adminTitle,
        private readonly LoggerInterface                              $logger
    ) {
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
        try {
            $object = $this->adminRoutingFromRequestFinder->getAdminRouteObject(
                $event->getRequest()
            );
            // should handle function and type relevant to it
            $event->getRequest()->attributes->set(TwigConstants::UI_WEB_PAGE_TITLE,
                $this->adminTitle->getTitle($object));

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