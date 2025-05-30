<?php

namespace Silecust\WebShop\EventSubscriber\Admin\Employee\Customer;

use Silecust\WebShop\Event\Component\UI\Panel\List\GridPropertyEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

readonly class OnGridPropertyEvent implements EventSubscriberInterface
{
    /**
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(private readonly RouterInterface $router)
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

        // for testing and UI both
        if (!in_array($route['_route'], ['sc_admin_panel','sc_admin_customer_list']))
            return;

        $event->setListGridProperties([
            'title' => 'Customer Address',
            'link_id' => 'id-customer',
            'columns' => [
                [
                    'label' => 'First Name',
                    'propertyName' => 'firstName',
                    'action' => 'display',
                ],
                [
                    'label' => 'Middle Name',
                    'propertyName' => 'middleName'
                ],
                [
                    'label' => 'Last Name',
                    'propertyName' => 'lastName'
                ],
                [
                    'label' => 'Given Name',
                    'propertyName' => 'givenName'
                ]
            ],
            'config' => [
                'create_link' => [
                    'link_id' => ' id-create-address',
                    'route' => 'sc_customer_create',
                    'anchorText' => 'Create address',
                    'redirect_upon_success_route' => 'sc_admin_customer_list'
                ],
                'edit_link' => [
                    'link_id' => ' id-edit-address',
                    'route' => 'sc_customer_edit',
                    'anchorText' => 'Edit Address',
                    'redirect_upon_success_route' => 'sc_admin_customer_list'
                ],
                'display_link' => [
                    'link_id' => ' id-display-address',
                    'route' => 'sc_customer_display',
                    'anchorText' => 'Display Address',
                    'redirect_upon_success_route' => 'sc_admin_customer_list'
                ]
            ],

        ]);
    }
}