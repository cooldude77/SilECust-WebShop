<?php

namespace Silecust\WebShop\Event\Component\Database;

use Doctrine\ORM\Query;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

class ListQueryEvent extends Event
{
    public const string BEFORE_LIST_QUERY = 'admin.query.before.list';
    private ?Query $query =null;

    public function __construct(private readonly Request $request, private readonly ?array $data = null)
    {
    }

    public function getQuery(): Query
    {
        return $this->query;
    }

    public function setQuery(Query $query): void
    {
        $this->query = $query;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getData(): ?array
    {
        return $this->data;
    }


}