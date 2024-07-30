<?php

namespace App\EventSubscriber\Admin\Customer\Transaction\Twig;

use App\Entity\OrderHeader;
use App\Event\Component\UI\TwigGridColumnEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class OnTwigGridColumnEvent implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            TwigGridColumnEvent::BEFORE_GRID_COLUMN_DISPLAY => 'beforeDisplay'
        ];

    }

    public function beforeDisplay(TwigGridColumnEvent $event): void
    {

        $data = $event->getData();
        $column = $event->getData()['column'];
        $listGrid = $event->getData()['listGrid'];

        /** @var OrderHeader $entity */
        $entity = $event->getData()['entity'];


        if ($listGrid['function'] == 'order') {
            switch ($column['propertyName']) {
                case 'dateTimeOfOrder':
                    {
                        $column['value'] = $entity->getDateTimeOfOrder()->format('d-m-Y');
                        $data['column'] = $column;
                    }
                    break;
            }

            $event->setData($data);

        }
    }
}