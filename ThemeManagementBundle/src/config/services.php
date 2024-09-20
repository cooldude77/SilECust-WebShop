<?php


use Silecust\ThemeManagementBundle\EventSubscriber\ResponseSubscriber;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {

    $services = $container->services();
    $container
        ->services()
        ->set('theme.management.kernel_view.event_subscriber', ResponseSubscriber::class)
        ->args(['$logger' => service('monolog.logger'),
            '$router' => service('router.default')])
        ->tag('kernel.event_subscriber');


};
