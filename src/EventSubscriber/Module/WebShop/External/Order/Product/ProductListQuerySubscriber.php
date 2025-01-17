<?php

namespace App\EventSubscriber\Module\WebShop\External\Order\Product;

use App\Event\Module\WebShop\External\Product\ProductListingQueryEvent;
use App\Service\Module\WebShop\External\Product\ProductListQueryBuilder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class ProductListQuerySubscriber implements EventSubscriberInterface
{
    public function __construct(private ProductListQueryBuilder $queryBuilder)
    {
    }

    public function onProductListQuery(ProductListingQueryEvent $event): void
    {
        $query = $this->queryBuilder->getQuery($event->getRequest());
        $event->setQuery($query);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'product.list.query' => 'onProductListQuery',
        ];
    }
}
