<?php

namespace Silecust\WebShop\EventSubscriber\Admin\Employee\Customer\Address\Grid;

use Silecust\WebShop\Controller\MasterData\Customer\Address\CustomerAddressController;
use Silecust\WebShop\Event\Component\UI\Panel\List\GridPropertyEvent;
use Silecust\WebShop\Service\Component\Event\EventRouteChecker;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * For Address Event
 */
readonly class OnGridPropertyEvent implements EventSubscriberInterface
{
    /**
     * @param RouterInterface $router
     */
    public function __construct(private readonly EventRouteChecker $eventRouteChecker
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            GridPropertyEvent::EVENT_NAME => 'setProperty'
        ];

    }

    public function setProperty(GridPropertyEvent $event): void
    {


        if (!
        $this->eventRouteChecker->isInRouteList($event->getRequest(), [
            'sc_admin_panel', 'sc_admin_customer_display'
        ]))
            return;

        // Note: Route
        // This is for success of testing as the URLS are called directly
        if ($this->eventRouteChecker->isAdminRoute($event->getRequest()))
            if (!$this->eventRouteChecker->checkFunctions($event->getRequest(), ['customer', 'customer_address']))
                return;

        if ($event->getData()['event_caller'] != CustomerAddressController::LIST_IDENTIFIER)
            return;

        $event->setListGridProperties([
            'title' => 'Customer Address',
            'link_id' => 'id-customer_address',
            'function' => 'customer_address',
            'id' => $event->getData()['id'],
            'grid_heading'=>'Addresses',
            'columns' => [
                [
                    'label' => 'Line 1',
                    'propertyName' => 'line1',
                    'action' => 'display',
                ]
            ],
            'config' => [
                'create_link' => [
                    'link_id' => ' id-create-address',
                    'route' => 'sc_admin_address_create',
                    'anchorText' => 'Create Order',
                    'redirect_upon_success_route' => 'sc_admin_addresses'
                ],
                'edit_link' => [
                    'link_id' => ' id-edit-address',
                    'route' => 'sc_admin_address_edit',
                    'anchorText' => 'Edit Address',
                    'redirect_upon_success_route' => 'sc_admin_addresses'
                ],
                'display_link' => [
                    'link_id' => ' id-display-address',
                    'route' => 'sc_admin_address_display',
                    'anchorText' => 'Display Address',
                    'redirect_upon_success_route' => 'sc_admin_addresses'
                ]
            ],

        ]);
    }

}