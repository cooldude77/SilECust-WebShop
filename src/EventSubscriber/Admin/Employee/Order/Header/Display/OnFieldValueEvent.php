<?php

namespace Silecust\WebShop\EventSubscriber\Admin\Employee\Order\Header\Display;

use Silecust\WebShop\Event\Component\UI\Panel\Display\DisplayFieldValueEvent;
use Silecust\WebShop\Exception\MasterData\Pricing\Item\PriceProductBaseNotFound;
use Silecust\WebShop\Exception\MasterData\Pricing\Item\PriceProductTaxNotFound;
use Silecust\WebShop\Service\Admin\Employee\Route\AdminRoutingFromRequestFinder;
use Silecust\WebShop\Service\Transaction\Admin\Order\Header\Display\OrderHeaderFieldDisplayMapper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 *
 */
readonly class OnFieldValueEvent implements EventSubscriberInterface
{
    /**
     * @param RouterInterface $router
     */
    public function __construct(private  OrderHeaderFieldDisplayMapper $orderHeaderFieldDisplayMapper,
                                private  RouterInterface               $router,
                                private  AdminRoutingFromRequestFinder $adminRoutingFromRequestFinder)
    {

    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            DisplayFieldValueEvent::EVENT_NAME => 'beforeDisplay'
        ];

    }

    /**
     * @param DisplayFieldValueEvent $event
     * @return void
     * @throws PriceProductBaseNotFound
     * @throws PriceProductTaxNotFound
     */
    public function beforeDisplay(DisplayFieldValueEvent $event): void
    {


        $route = $this->router->match($event->getData()['request']->getPathInfo());

        // To check for both stand-alone test case where admin panel is not called
        // and also where admin panel is called from UI
        if (!in_array($route['_route'], ['sc_admin_panel', 'sc_admin_order_display']))
            return;

        // if admin panel is called
        // check proper function
        if (in_array($route['_route'], ['sc_admin_panel'])) {
            $object = $this->adminRoutingFromRequestFinder->getAdminRouteObject($event->getData()['request']);

            if ($object->getFunction() != 'order')
                return;

        }
        $data = $this->mapData($event, $route['_route']);

        $event->setData($data);

    }

    /**
     * @param DisplayFieldValueEvent $event
     * @param $route1
     * @return mixed
     * @throws PriceProductBaseNotFound
     * @throws PriceProductTaxNotFound
     */
    public function mapData(DisplayFieldValueEvent $event, $route1): mixed
    {
        return $this->orderHeaderFieldDisplayMapper->mapData($event, $route1);
    }


}
