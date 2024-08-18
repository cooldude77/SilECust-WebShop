<?php

namespace App\Event\Component\UI\Twig;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * Set when order list event is called, here you can set list properties dynamically
 *
 */
class GridPropertyEvent extends Event
{
    public const string LIST_GRID_PROPERTY_FOR_ORDERS = 'transaction.list_grid.property';
    private ?array $data;


    public function __construct(array $data =null)
    {
        $this->data = $data;
    }

    /** @var array  */
    private array $listGridProperties = array();

    public function getListGridProperties(): array
    {
        return $this->listGridProperties;
    }

    public function setListGridProperties(array $listGridProperties): void
    {
        $this->listGridProperties = $listGridProperties;
    }

    public function getData(): ?array
    {
        return $this->data;
    }


}