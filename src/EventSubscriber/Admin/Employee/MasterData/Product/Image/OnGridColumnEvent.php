<?php

namespace Silecust\WebShop\EventSubscriber\Admin\Employee\MasterData\Product\Image;

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

        if (!$this->eventRouteChecker
            ->checkIfAdminOrDirectInvocationTrue(
                $event->getData()['request'],
                'sc_admin_product_file_image_list',
                'product_file_image',
                'list'
            )
        )
            return;

        $data = $event->getData();

        $column = $event->getData()['column'];
        /** @var \Silecust\WebShop\Entity\ProductImage $entity */
        $entity = $event->getData()['entity'];


        switch ($column['propertyName']) {
            case 'yourFileName':
                $column['value'] = $entity->getFile()->getYourFileName();
                $data['column'] = $column;
                break;
            case 'name':
                $column['value'] = $entity->getFile()->getName();
                $data['column'] = $column;
                break;

        }

        $event->setData($data);

    }
}
