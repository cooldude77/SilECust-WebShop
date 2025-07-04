<?php

namespace Silecust\WebShop\EventSubscriber\Admin\Customer\Order\Header;

use Silecust\WebShop\Event\Component\UI\Panel\Display\DisplayParamPropertyEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

readonly class OnDisplayParamPropertyEvent implements EventSubscriberInterface
{
    /**
     * @param RouterInterface $router
     */
    public function __construct(private readonly RouterInterface $router
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

        if ($route['_route'] != 'sc_my_order_display')
            return;


        $event->setDisplayParamProperties(
            [
                'title' => 'Order',
                'link_id' => 'id-order',
                'config' => [
                    'edit_link' => [
                        'edit_link_allowed' => false,
                        'link_id' => 'id-order-header-edit'
                    ]
                ],
                'fields' => [
                    [
                        'label' => 'Order Number',
                        'propertyName' => 'generatedId',
                        'action' => 'display',
                    ],
                    [
                        'label' => 'Date Of Order',
                        'propertyName' => 'dateTimeOfOrder'
                    ],
                    [
                        'label' => 'Status',
                        'propertyName' => 'orderStatusType'
                    ],
                    [
                        'label' => 'Order Value',
                        'propertyName' => 'orderValue'
                    ],
                ],
            ]
        );
    }

}