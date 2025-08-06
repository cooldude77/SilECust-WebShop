<?php

namespace Silecust\WebShop\EventSubscriber\Admin\Employee\Customer\Address\Grid\Column;

use Silecust\WebShop\Controller\MasterData\Customer\Address\CustomerAddressController;
use Silecust\WebShop\Entity\CustomerAddress;
use Silecust\WebShop\Event\Component\UI\Panel\List\GridColumnEvent;
use Silecust\WebShop\Service\Component\Event\EventRouteChecker;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 *
 */
readonly class OnGridColumnEvent implements EventSubscriberInterface
{
    /**
     * @param \Silecust\WebShop\Service\Component\Event\EventRouteChecker $eventRouteChecker
     */
    public function __construct(private EventRouteChecker $eventRouteChecker)
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


        if (!
        $this->eventRouteChecker->isInRouteList($event->getData()['request'], [
            'sc_admin_panel', 'sc_admin_customer_display'
        ]))
            return;

        // Note: Route
        // This is for success of testing as the URLS are called directly
        if ($this->eventRouteChecker->isAdminRoute($event->getData()['request']))
            if (!$this->eventRouteChecker->checkFunctions($event->getData()['request'], ['customer', 'customer_address']))
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
