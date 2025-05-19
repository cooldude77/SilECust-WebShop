<?php

namespace Silecust\WebShop\Service\Twig\UI\Panel\Event\Provider\Display;

use Silecust\WebShop\Event\Component\UI\Panel\Display\DisplayEditLinkEvent;
use Silecust\WebShop\Event\Component\UI\Panel\Display\DisplayFieldValueEvent;

class EventProvider
{


    public function provideDisplayEditLinkEvent(): DisplayEditLinkEvent
    {
        return new DisplayEditLinkEvent();
    }

    public function provideFieldValueEvent(): DisplayFieldValueEvent
    {
        return new DisplayFieldValueEvent();
    }

}
