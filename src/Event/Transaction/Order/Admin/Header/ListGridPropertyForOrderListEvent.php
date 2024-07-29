<?php

namespace App\Event\Transaction\Order\Admin\Header;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * Set when order list event is called, here you can set list properties dynamically
 *
 */
class ListGridPropertyForOrderListEvent extends Event
{
    public const string LIST_GRID_PROPERTY_FOR_ORDERS = 'transaction.list_grid.property';


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


}