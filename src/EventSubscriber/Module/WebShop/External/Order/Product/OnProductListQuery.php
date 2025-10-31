<?php

namespace Silecust\WebShop\EventSubscriber\Module\WebShop\External\Order\Product;

use Silecust\WebShop\Event\Module\WebShop\External\Product\ProductListingQueryEvent;
use Silecust\WebShop\Exception\Module\WebShop\External\Product\InvalidCategorySearchFilter;
use Silecust\WebShop\Service\Module\WebShop\External\Product\ProductListQueryBuilder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class OnProductListQuery implements EventSubscriberInterface
{
    public function __construct(private ProductListQueryBuilder $queryBuilder)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'product.list.query' => 'onProductListQuery',
        ];
    }

    public function onProductListQuery(ProductListingQueryEvent $event): void
    {
        try {
            $query = $this->queryBuilder->getQuery($event->getRequest());
        } catch (InvalidCategorySearchFilter $e) {
            $event->stopPropagation();
            return;
        }
        $event->setQuery($query);
    }
}
