<?php

namespace Silecust\WebShop;

use Silecust\WebShop\Resources\SilecustCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class SilecustWebShopBundle extends AbstractBundle
{
    const string CONFIG_EXTS = '.{php,xml,yaml,yml}';


    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {

        // prepend config from a file
        $container->import(__DIR__ . '/Resources/config/services.yaml');
        $container->import(__DIR__ . '/Resources/config/doctrine_migrations.yaml');
        //  $container->import(__DIR__ . '/Resources/config/routes.yaml');
    }

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new SilecustCompilerPass());
    }
}