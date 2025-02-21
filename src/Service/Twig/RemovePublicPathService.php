<?php

namespace Silecust\WebShop\Service\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class RemovePublicPathService extends AbstractExtension
{

    public function getFilters(): array
    {
        return [
            new TwigFilter('removePublicPathSegment', [$this, 'removePublicPathSegment']),
        ];
    }

    public function removePublicPathSegment($path): string
    {
        return substr($path, strlen('/public/'),strlen($path) );
    }
}