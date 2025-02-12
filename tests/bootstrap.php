<?php

use App\Kernel;
use Doctrine\Deprecations\Deprecation;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__) . '/vendor/autoload.php';

if (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__) . '/.env');
}

if ($_SERVER['APP_DEBUG']) {
    umask(0000);
}

if (class_exists(Deprecation::class)) {
    Deprecation::enableWithTriggerError();
}
// This has been enhanced on the base of DamaDoctrineTestBundle
// Since it does not commit, the entities will not be seen in database
// but the auto increments will continue to increase in tests
// So beware of that scenario

bootstrap();
function bootstrap(): void
{
    $kernel = new \App\Tests\TestKernel('test', true);
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