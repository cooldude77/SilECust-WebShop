<?php

namespace App\EventSubscriber\MasterData\Category;

use App\Event\Admin\Employee\FrameWork\PreHeadForwardingEvent;
use App\Service\Admin\Employee\FrameWork\AdminRoutingFromRequestFinder;
use App\Service\MasterData\Category\CategoryService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class OnPreHeadForwardingEvent implements EventSubscriberInterface
{
    public function __construct(private AdminRoutingFromRequestFinder
    $adminRoutingFromRequestFinder,
   private readonly CategoryService $categoryService)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PreHeadForwardingEvent::PRE_HEAD_FORWARDING_EVENT => 'setHeadData'
        ];

    }

    public function setHeadData(PreHeadForwardingEvent $event)
    {
       $object=  $this->adminRoutingFromRequestFinder->getAdminRouteObject($event->getRequest());
        $event->setPageTitle($this->categoryService->getTitle($object->getType(),$object->getId()));
    }
}