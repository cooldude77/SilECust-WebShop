<?php

namespace Silecust\WebShop\EventSubscriber\Admin\Employee\Customer\Address\Grid;

use Silecust\WebShop\Controller\MasterData\Customer\Address\CustomerAddressController;
use Silecust\WebShop\Controller\MasterData\Customer\CustomerController;
use Silecust\WebShop\Event\Component\UI\Panel\List\GridPropertyEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

readonly class OnGridPropertyEvent implements EventSubscriberInterface
{
    /**
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(private readonly RouterInterface $router
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

        $route = $this->router->match($event->getRequest()->getPathInfo());

        if (!in_array($route['_route'], ['sc_admin_panel', 'sc_admin_customer_display']))
            return;

        if ($event->getData()['event_caller'] != CustomerAddressController::LIST_IDENTIFIER)
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