<?php

namespace App;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class SilecustWebShopBundle extends AbstractBundle
{
    /**
     * @throws \Exception
     */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        /*
          // not able to load container this way
            $loader = new PhpFileLoader($builder, new FileLocator(__DIR__ . '/../src/Resources/config'));
           $loader->load('web_shop.php');

        */
        $loader = new YamlFileLoader($builder, new FileLocator(__DIR__ . '/../src/Resources/config'));
        $loader->load('database.yaml');
        $loader = new YamlFileLoader($builder, new FileLocator(__DIR__ . '/../src/Resources/config'));
        $loader->load('framework.yaml');
        $loader = new YamlFileLoader($builder, new FileLocator(__DIR__ . '/../src/Resources/config'));
        $loader->load('authentication.yaml');
        $loader = new YamlFileLoader($builder, new FileLocator(__DIR__ . '/../src/Resources/config'));
        $loader->load('master_data.yaml');
        $loader = new YamlFileLoader($builder, new FileLocator(__DIR__ . '/../src/Resources/config'));
        $loader->load('transactions.yaml');
        $loader = new YamlFileLoader($builder, new FileLocator(__DIR__ . '/../src/Resources/config'));
        $loader->load('web_shop.yaml');

    }
}