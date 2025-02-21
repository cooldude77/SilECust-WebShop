<?php

namespace Silecust\WebShop;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class SilecustWebShopBundle extends AbstractBundle
{
    const string CONFIG_EXTS = '.{php,xml,yaml,yml}';


    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {

        // prepend config from a file
        $container->import(__DIR__ . '/Resources/config/doctrine_migrations.yaml');
    }


}