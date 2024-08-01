<?php

namespace App\Event\Component\UI;

use Symfony\Contracts\EventDispatcher\Event;

class TwigGridColumnEvent extends Event
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

    public function isDataChanged(): bool
    {
        return $this->dataChanged;
    }

    public function setDataChanged(bool $dataChanged): void
    {
        $this->dataChanged = $dataChanged;
    }


}