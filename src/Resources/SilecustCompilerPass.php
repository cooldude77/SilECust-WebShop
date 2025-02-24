<?php

namespace Silecust\WebShop\Resources;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SilecustCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $x = 0;
    }
}