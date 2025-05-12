<?php

namespace Silecust\WebShop\EventSubscriber\Module\WebShop\External\Order\Product;

use Silecust\WebShop\Entity\PriceProductBase;
use Silecust\WebShop\Entity\Product;
use Silecust\WebShop\Event\Module\WebShop\External\Product\Twig\SingleProductInProductListDisplayEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class OnSingleProductInProductListDisplay implements EventSubscriberInterface
{
    public function __construct()
    {
    }

    public function onProductDisplay(SingleProductInProductListDisplayEvent $event): void
    {



        switch (get_class($event->getEntity())) {

            case Product::class:
                $entity = $event->getEntity();
                $event->setProduct($entity);
                break;

            case PriceProductBase::class:
                /** @var PriceProductBase $entity */
                $entity = $event->getEntity();
                $event->setProduct($entity->getProduct());
                break;


        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'web_shop.product_list.single_product_display' => 'onProductDisplay',
        ];
    }
}
