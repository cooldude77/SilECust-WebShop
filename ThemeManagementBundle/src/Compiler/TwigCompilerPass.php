<?php

namespace Silecust\ThemeManagementBundle\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TwigCompilerPass implements CompilerPassInterface
{

    /**
     * @throws \Exception
     */
    public function process(ContainerBuilder $container)
    {
        $srv = $container->getDefinition('theme.management.kernel_view.event_subscriber');
        $srv->addArgument(new Reference('monolog.logger'));
    }
}