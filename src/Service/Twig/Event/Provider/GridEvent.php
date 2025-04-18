<?php

namespace Silecust\WebShop\Service\Twig\Event\Provider;

use Silecust\WebShop\Event\Component\UI\Panel\List\GridColumnEvent;
use Silecust\WebShop\Event\Component\UI\Panel\List\GridCreateLinkEvent;

class GridEvent
{

    public function provideGridColumnEvent(): GridColumnEvent
    {
        return new GridColumnEvent();

    }

    public function provideGridCreateLinkEvent(): GridCreateLinkEvent
    {
        return new GridCreateLinkEvent();
    }
}
