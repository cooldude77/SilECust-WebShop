<?php

namespace Silecust\WebShop\EventSubscriber\Admin\Customer\Address\Grid\Column;

use Silecust\WebShop\Entity\CustomerAddress;
use Silecust\WebShop\Event\Component\UI\Panel\List\GridColumnEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 *
 */
readonly class OnGridColumnEvent implements EventSubscriberInterface
{
    /**
     * @param RouterInterface $router
     */
    public function __construct(private RouterInterface $router)
    {
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            GridColumnEvent::EVENT_NAME => 'beforeDisplay'
        ];

    }

    /**
     * @param GridColumnEvent $event
     * @return void
     */
    public function beforeDisplay(GridColumnEvent $event): void
    {

        $route = $this->router->match($event->getData()['request']->getPathInfo());

        if ($route['_route'] != 'sc_my_addresses')
            return;

        $data = $event->getData();
        $column = $event->getData()['column'];
        /** @var \Silecust\WebShop\Entity\CustomerAddress $entity */
        $entity = $event->getData()['entity'];


        switch ($column['propertyName']) {
            case 'forShipping':
                $column['value'] = $entity->getAddressType() == CustomerAddress::ADDRESS_TYPE_SHIPPING;
                $data['column'] = $column;
                break;
            case 'forBilling':
                $column['value'] = $entity->getAddressType() == CustomerAddress::ADDRESS_TYPE_BILLING;
                $data['column'] = $column;
                break;
            case 'defaultShipping':
                $column['value'] = $entity->getAddressType() == CustomerAddress::ADDRESS_TYPE_SHIPPING
                    && $entity->isDefault();
                $data['column'] = $column;
                break;
            case 'defaultBilling':
                $column['value'] = $entity->getAddressType() == CustomerAddress::ADDRESS_TYPE_BILLING
                    && $entity->isDefault();
                $data['column'] = $column;
                break;

        }
        $event->setData($data);

    }
}
