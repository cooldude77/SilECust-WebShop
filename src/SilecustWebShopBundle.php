<?php

namespace Silecust\WebShop;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class SilecustWebShopBundle extends AbstractBundle
{
    const string CONFIG_EXTS = '.{php,xml,yaml,yml}';


    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        echo __DIR__ . '/Resources/config/*';

       // $container->import(__DIR__ . '/Resources/config/*' . self::CONFIG_EXTS, 'glob');
        $container->import(__DIR__ . '/Resources/config/repository.yaml');
        
    }


}