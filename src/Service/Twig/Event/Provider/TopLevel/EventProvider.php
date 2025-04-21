<?php

namespace Silecust\WebShop\Service\Twig\Event\Provider\TopLevel;

use Silecust\WebShop\Event\Component\UI\Panel\TopLevel\DisplayLinkEvent;
use Silecust\WebShop\Event\Component\UI\Panel\TopLevel\TopLevelEditLinkEvent;

class EventProvider
{


    public function provideTopLevelEditLinkEvent(): TopLevelEditLinkEvent
    {
        return new TopLevelEditLinkEvent();
    }

    public function provideDisplayLinkEvent(): DisplayLinkEvent
    {
        return new DisplayLinkEvent();
    }

}
