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
                    ]
                ],

            ]);
        }
}