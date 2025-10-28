<?php

use Doctrine\Deprecations\Deprecation;
use Silecust\WebShop\Tests\TestKernel;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

require dirname(__DIR__) . '/vendor/autoload.php';

if (class_exists(Deprecation::class)) {
    Deprecation::enableWithTriggerError();
}

bootstrap();
/**
 * @return void
 * @throws \Exception
 */
function bootstrap(): void
{
    $kernel = new TestKernel('test', true);
    $kernel->boot();

    $application = new Application($kernel);
    $application->setCatchExceptions(false);
    $application->setAutoExit(false);

    $application->run(new ArrayInput(['command' => 'doctrine:database:drop', '--if-exists' => '1', '--force' => '1',]));

    $application->run(new ArrayInput(['command' => 'doctrine:database:create', '--no-interaction' => true]));

    $application->run(new ArrayInput(['command' => 'doctrine:migrations:migrate', '--no-interaction' => true]));

    //    $application->run(new ArrayInput(['command' => 'doctrine:fixtures:load', '--no-interaction'
    // => true,'--append'=>true]));

    $kernel->shutdown();
}