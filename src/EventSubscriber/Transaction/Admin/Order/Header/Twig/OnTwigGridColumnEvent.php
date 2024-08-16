<?php

namespace App\EventSubscriber\Transaction\Admin\Order\Header\Twig;

use App\Entity\OrderHeader;
use App\Event\Component\UI\Twig\TwigGridColumnEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

class OnTwigGridColumnEvent implements EventSubscriberInterface
{
    public function __construct(private readonly RouterInterface $router)
    {
    }

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
                case 'id':
                    $column['value'] = $this->router->generate('my_order_display', ['id'=>$entity->getId()]);
                    $data['column'] = $column;
                    break;
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