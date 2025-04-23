<?php

namespace Silecust\WebShop\EventSubscriber\Admin\Customer\Address\Grid;

use Silecust\WebShop\Event\Component\UI\Panel\List\GridPropertyEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

readonly class OnGridPropertyEvent implements EventSubscriberInterface
{
    /**
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(private AuthorizationCheckerInterface $authorizationChecker,
                                private readonly RouterInterface      $router
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

        if (!in_array($route['_route'], ['sc_my_addresses']))
            return;

        /**
         *
         * if ($this->authorizationChecker->isGranted('ROLE_EMPLOYEE')) {
         *
         * Todo: check for these
         * if (!($event->getRequest()->query->get('_function') == 'order'
         * && $event->getRequest()->query->get('_type') == 'list')
         * )
         *
         *
         *
         * $event->setListGridProperties([
         * 'function' => 'customer_address',
         * 'title' => 'Customer Address',
         * 'link_id' => 'id-customer_address',
         * 'edit_link_allowed' => true,
         * 'columns' => [
         * [
         * 'label' => 'id',
         * 'propertyName' => 'id',
         * 'action' => 'display',
         * ],
         * [
         * 'label' => 'Line 1',
         * 'propertyName' => 'line1'
         * ]
         * ],
         * 'createButtonConfig' => [
         * 'link_id' => ' id-create-address',
         * 'function' => 'order',
         * 'anchorText' => 'Create Order'
         * ]
         * ]);
         *
         * } else {
         */

        if ($this->authorizationChecker->isGranted('ROLE_CUSTOMER')) {
            $event->setListGridProperties([
                'title' => 'Customer Address',
                'link_id' => 'id-customer_address',
                'columns' => [
                    [
                        'label' => 'id',
                        'propertyName' => 'id',
                        'action' => 'display',
                    ],
                    [
                        'label' => 'Line 1',
                        'propertyName' => 'line1'
                    ]
                ],
                'config' => [
                    'create_link' => [
                        'link_id' => ' id-create-address',
                        'route' => 'sc_my_address_create',
                        'anchorText' => 'Create Order',
                        'redirect_upon_success_route' => 'sc_my_addresses'
                    ],
                    'edit_link' => [
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
                    ]
                ],

            ]);
        }
    }
}