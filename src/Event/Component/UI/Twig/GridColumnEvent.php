<?php

namespace App\Event\Component\UI\Twig;

use Symfony\Contracts\EventDispatcher\Event;

class GridColumnEvent extends Event
{

    private  bool $dataChanged = false;
    const string BEFORE_GRID_COLUMN_DISPLAY = 'panel.grid.before_column_display';

    private mixed $data;

    public function getData(): mixed
    {
        return $this->data;
    }

    public function setData(mixed $data): void
    {
        $this->data = $data;
    }




}