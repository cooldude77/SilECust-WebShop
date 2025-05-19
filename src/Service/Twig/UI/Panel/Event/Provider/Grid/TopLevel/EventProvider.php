<?php

namespace Silecust\WebShop\Service\Twig\UI\Panel\Event\Provider\Grid\TopLevel;

use Silecust\WebShop\Event\Component\UI\Panel\List\TopLevel\DisplayLinkEvent;
use Silecust\WebShop\Event\Component\UI\Panel\List\TopLevel\TopLevelEditLinkEvent;

class EventProvider
{


    public function provideTopLevelEditLinkEvent(): TopLevelEditLinkEvent
    {
        return new TopLevelEditLinkEvent();
    }


}
