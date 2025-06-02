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

        if (!in_array($route['_route'], ['sc_admin_panel', 'sc_my_address_display']))
            return;


        $event->setDisplayParamProperties(
            [
                'title' => 'Customer Address',
                'link_id' => 'id-customer-address',
                'config' => [
                    'edit_link' => [
                        'editButtonLinkText' => 'Edit',
                        'route' => 'sc_my_address_edit',
                        'link_id' => 'id-display-customer-address'
                    ]
                ],
                'fields' => [
                    [
                        'label' => 'line 1',
                        'propertyName' => 'line1',
                    ], [
                        'label' => 'line 2',
                        'propertyName' => 'line2',
                    ], [
                        'label' => 'line 3',
                        'propertyName' => 'line3',
                    ],
                ]]
        );
    }

}