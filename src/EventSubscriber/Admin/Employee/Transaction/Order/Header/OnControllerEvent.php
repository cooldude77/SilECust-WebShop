<?php /** @noinspection ALL */

/** @noinspection PhpUnused */

namespace Silecust\WebShop\EventSubscriber\Admin\Employee\Transaction\Order\Header;

use Silecust\WebShop\Controller\Transaction\Order\Admin\Header\OrderHeaderController;
use Silecust\WebShop\Event\Admin\Employee\FrameWork\Head\PreHeadForwardingEvent;
use Silecust\WebShop\Repository\OrderHeaderRepository;
use Silecust\WebShop\Service\Admin\Employee\Route\AdminRoutingFromRequestFinder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Controller\ErrorController;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

readonly class OnControllerEvent implements EventSubscriberInterface
{

    public function __construct(private readonly AdminRoutingFromRequestFinder $adminRoutingFromRequestFinder,
                                private OrderHeaderRepository                  $orderHeaderRepository)
    {

    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::CONTROLLER => 'updateGeneratedId'];

    }

    /**
     * @param PreHeadForwardingEvent $event
     *
     * @return void
     * @noinspection PhpUnused
     */
    public function updateGeneratedId(ControllerEvent $event): void
    {

        $controller = $event->getController();
        if ($controller instanceof ErrorController)
            return;
        if ($controller[0] instanceof OrderHeaderController &&
            ($controller[1] == 'edit' ||$controller[1] == 'display' )) {
            {
                $orderHeader = $this->orderHeaderRepository->find($event->getRequest()->attributes->get('id'));
                $event->getRequest()->attributes->set('generatedId', $orderHeader->getGeneratedId());

            }
        }
    }
}