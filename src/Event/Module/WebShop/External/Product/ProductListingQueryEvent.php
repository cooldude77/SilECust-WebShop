<?php

namespace Silecust\WebShop\Event\Module\WebShop\External\Product;

use Doctrine\ORM\Query;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

class ProductListingQueryEvent extends Event
{
    const string LIST_QUERY_EVENT = 'product.list.query';

    private ?Query $query = null;

    /**
     * @param Request $request
     */
    public function __construct(private readonly Request $request)
    {
    }

    public function getQuery(): ?Query
    {
        return $this->query;
    }

    public function setQuery(?Query $query): void
    {
        $this->query = $query;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }
}