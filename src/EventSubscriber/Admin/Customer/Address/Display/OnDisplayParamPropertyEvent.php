<?php

namespace Silecust\WebShop\EventSubscriber\Admin\Customer\Address\Display;

use Silecust\WebShop\Event\Component\UI\Panel\Display\DisplayParamPropertyEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

readonly class OnDisplayParamPropertyEvent implements EventSubscriberInterface
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
            DisplayParamPropertyEvent::EVENT_NAME => 'setProperty'
        ];

    }

    public function setProperty(DisplayParamPropertyEvent $event): void
    {

        $route = $this->router->match($event->getRequest()->getPathInfo());

        if (!in_array($route['_route'], ['sc_my_address_display']))
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
            $event->setDisplayParamProperties(
                ['title' => 'Customer Address',
                    'link_id' => 'id-customer-address',
                    'config' => [
                        'edit_link' => [
                            'editButtonLinkText' => 'Edit',
                            'route' => 'sc_my_address_edit',
                            'link_id' => 'id-display-customer-address']
                    ],
                    'fields' => [
                        ['label' => 'line 1',
                            'propertyName' => 'line1',
                        ],
                    ]]
            );
        }
    }
}