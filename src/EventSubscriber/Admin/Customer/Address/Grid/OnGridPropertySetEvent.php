<?php

namespace Silecust\WebShop\EventSubscriber\Admin\Customer\Address\Grid;

use Silecust\WebShop\Event\Component\UI\Panel\List\GridPropertyEvent;
use Silecust\WebShop\Service\Component\Event\EventRouteChecker;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class OnGridPropertySetEvent implements EventSubscriberInterface
{
    /**
     * @param \Silecust\WebShop\Service\Component\Event\EventRouteChecker $eventRouteChecker
     */
    public function __construct(private EventRouteChecker $eventRouteChecker
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
        $this->eventRouteChecker->isInRouteList($event->getRequest(), ['sc_my_addresses']))
            return;

        $event->setListGridProperties([
                'title' => 'Customer Address',
                'link_id' => 'id-customer_address',
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
                        'create_link_allowed' => true,
                        'route' => 'sc_my_address_create',
                        'anchorText' => 'Address',
                        'redirect_upon_success_route' => 'sc_my_addresses'
                    ],
                    'edit_link' => [
                        'edit_link_allowed' => true,
                        'link_id' => ' id-edit-address',
                        'route' => 'sc_my_address_edit',
                        'anchorText' => 'Edit Address',
                        'redirect_upon_success_route' => 'sc_my_addresses'
                    ],
                    'display_link' => [
                        'link_id' => ' id-display-address',
                        'route' => 'sc_my_address_display',
                        'anchorText' => 'Display Address',
                        'redirect_upon_success_route' => 'sc_my_addresses'
                    ],
                    'delete_link' => [
                        'delete_link_allowed' => true,
                        'link_id' => 'id-delete-address',
                        'route' => 'sc_my_address_delete',
                        'anchorText' => 'Delete Address',
                        'redirect_upon_success_route' => 'sc_my_address_delete'
                    ]
                ],

            ]);
        }
}