<?php

namespace Silecust\WebShop\Service\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigTest;

class TwigUtility extends AbstractExtension
{
    public function getTests(): array
    {
        return [
            new TwigTest('instance_of', [$this, 'isInstanceOf']),
        ];
    }

    public function isInstanceOf(mixed $value, string $type): bool
    {
        return (\function_exists($function = 'is_' . $type) && $function($value));

    }
}