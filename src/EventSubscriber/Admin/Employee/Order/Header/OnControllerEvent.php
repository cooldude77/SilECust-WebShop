<?php /** @noinspection ALL */

/** @noinspection PhpUnused */

namespace Silecust\WebShop\EventSubscriber\Admin\Employee\Order\Header;

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
        //todo : there is a discrepancy in URL for display and edit ( one uses id and other generated id )

        $controller = $event->getController();

        // The event sends inconsistent parameter types
        // is_array is check for ajax calls where the controller is a class itself and not an array
        if ($controller instanceof ErrorController || !is_array($controller))
            return;

        if ($controller[0] instanceof OrderHeaderController &&
            ($controller[1] == 'edit' || $controller[1] == 'display')) {
            {
                if ($event->getRequest()->attributes->get('id') != null)
                    $orderHeader = $this->orderHeaderRepository->find($event->getRequest()->attributes->get('id'));
                else
                    $orderHeader = $this->orderHeaderRepository
                        ->findOneBy(['generatedId' => $event->getRequest()->get('generatedId')]);

                $event->getRequest()->attributes->set('generatedId', $orderHeader->getGeneratedId());

            }
        }
    }
}