<?php

namespace Silecust\WebShop\EventSubscriber\Admin\Employee\MasterData\Category\Image;

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
            ->checkIfTrue(
                $event->getData()['request'],
                ['sc_admin_panel', 'sc_admin_category_file_image_list'],
                'category_file_image',
                'list'
            )
        )
            return;

        $data = $event->getData();

        $column = $event->getData()['column'];
        /** @var \Silecust\WebShop\Entity\CategoryImage $entity */
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
