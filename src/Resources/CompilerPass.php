<?php

namespace App\Resources;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class CompilerPass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {
        $twig = $container->getDefinition('twig.loader.native_filesystem');

        $twig->addMethodCall('addPath', [$container->getParameter('kernel.project_dir').'/vendor/silecust/silecust/templates']);

    }
}