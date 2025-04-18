<?php

namespace Silecust\WebShop\Service\Twig\Event\Provider;

use Silecust\WebShop\Event\Component\UI\Panel\List\GridColumnEvent;
use Silecust\WebShop\Event\Component\UI\Panel\List\GridCreateLinkEvent;
use Silecust\WebShop\Event\Component\UI\Panel\List\GridPaginationEvent;
use Silecust\WebShop\Event\Component\UI\Panel\List\GridRowDataEvent;
use Silecust\WebShop\Event\Component\UI\Panel\List\GridRowHeaderEvent;

class GridEventProvider
{

    public function provideGridColumnEvent(): GridColumnEvent
    {
        return new GridColumnEvent();

    }

    public function provideGridCreateLinkEvent(): GridCreateLinkEvent
    {
        return new GridCreateLinkEvent();
    }

    public function provideGridRowDataEvent(): GridRowDataEvent
    {
        return new GridRowDataEvent();
    }

    public function provideGridRowHeaderEvent(): GridRowHeaderEvent
    {
        return new GridRowHeaderEvent();
    }

    public function provideGridPaginationEvent(): GridPaginationEvent
    {
        return new GridPaginationEvent();
    }
}
