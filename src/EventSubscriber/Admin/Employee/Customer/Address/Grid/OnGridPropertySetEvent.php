<?php

namespace Silecust\WebShop\EventSubscriber\Admin\Employee\Customer\Address\Grid;

use Silecust\WebShop\Event\Component\UI\Panel\List\GridPropertySetEvent;
use Silecust\WebShop\Service\Component\Event\EventRouteChecker;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * For Address Event
 */
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
            GridPropertySetEvent::EVENT_NAME => 'setProperty'
        ];

    }

    public function setProperty(GridPropertySetEvent $event): void
    {


        if (!
        $this->eventRouteChecker->isInRouteList($event->getRequest(), [
            'sc_admin_panel', 'sc_admin_customer_display'
        ]))
            return;

        // Note: Route
        // This is for success of testing as the URLS are called directly
        if ($this->eventRouteChecker->isAdminRoute($event->getRequest()))
            // Pass when the top function is a customer, but you need to show the list of addresses as a grid
            // for that customer
            if (!$this->eventRouteChecker->checkFunctions($event->getRequest(), ['customer_address']))
                // set properties only for customer_address and not when customer grid is called
                // if (isset($event->getData()['event_caller'])
                //       && $event->getData()['event_caller'] != CustomerAddressController::LIST_IDENTIFIER)
                return;

        $event->setListGridProperties([
            'title' => 'Customer Address',
            'link_id' => 'id-customer_address',
            'function' => 'customer_address',
            'id' => $event->getData()['id'],
            'grid_heading' => 'Addresses',
            'columns' => [
                [
                    'label' => 'Line 1',
                    'propertyName' => 'line1',
                    'action' => 'display',

                ],
                [
                    'label' => 'For Shipping',
                    'propertyName' => 'forShipping',
                    'type' => 'icon',
                ],
                [
                    'label' => 'Default Shipping',
                    'propertyName' => 'defaultShipping',
                    'type' => 'icon',
                ],
                [
                    'label' => 'For Billing',
                    'propertyName' => 'forBilling',
                    'type' => 'icon',
                ],
                [
                    'label' => 'Default Billing',
                    'propertyName' => 'defaultBilling',
                    'type' => 'icon',
                ],
            ],
            'config' => [
                'create_link' => [
                    'link_id' => ' id-create-address',
                    'route' => 'sc_admin_address_create',
                    'anchorText' => 'Create Address',
                    'redirect_upon_success_route' => 'sc_admin_customer_address_list'
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
                ],
                'delete_link' => [
                    'delete_link_allowed' => true,
                    'link_id' => 'id-delete-address',
                    'route' => 'sc_admin_address_delete',
                    'anchorText' => 'Delete Address',
                    'redirect_upon_success_route' => 'sc_admin_addresses'
                ]
            ],

        ]);
    }

}