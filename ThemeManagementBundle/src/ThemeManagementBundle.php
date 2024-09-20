<?php

namespace Silecust\ThemeManagementBundle;

use Silecust\ThemeManagementBundle\Compiler\TwigCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class ThemeManagementBundle extends AbstractBundle
{

    public function build(ContainerBuilder $container)
    {

      //  $container->addCompilerPass(new TwigCompilerPass());
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import(dirname(__DIR__) . '/src/config/services.php');

    }
}