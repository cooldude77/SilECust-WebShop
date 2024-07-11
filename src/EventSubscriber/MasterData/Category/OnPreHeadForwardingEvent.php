<?php
/** @noinspection PhpUnused */

namespace App\EventSubscriber\MasterData\Category;

use App\Event\Admin\Employee\FrameWork\PreHeadForwardingEvent;
use App\Exception\Admin\Employee\FrameWork\AdminUrlFunctionKeyParameterNull;
use App\Exception\Admin\Employee\FrameWork\AdminUrlTypeKeyParameterNull;
use App\Service\Admin\Action\Exception\EmptyActionListMapException;
use App\Service\Admin\Action\Exception\FunctionNotFoundInMap;
use App\Service\Admin\Action\Exception\TypeNotFoundInMap;
use App\Service\Admin\Employee\FrameWork\AdminRoutingFromRequestFinder;
use App\Service\MasterData\Category\CategoryService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class OnPreHeadForwardingEvent implements EventSubscriberInterface
{
    public function __construct(private AdminRoutingFromRequestFinder $adminRoutingFromRequestFinder,
        private CategoryService $categoryService
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PreHeadForwardingEvent::PRE_HEAD_FORWARDING_EVENT => 'setHeadData'
        ];

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
            if ($object->getFunction() == 'category') {
                $event->setPageTitle(
                    $this->categoryService->getTitle($object->getType(), $object->getId())
                );
            }
        } catch (EmptyActionListMapException|FunctionNotFoundInMap|TypeNotFoundInMap
        |AdminUrlFunctionKeyParameterNull|AdminUrlTypeKeyParameterNull) {
            // do nothing
        }
    }
}