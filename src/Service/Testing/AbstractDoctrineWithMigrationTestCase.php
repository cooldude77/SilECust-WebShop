<?php

namespace Silecust\WebShop\Service\Testing;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * This class was used before using DAMA Doctrine bundle
 * Kept here as reference , may be deleted later
 */
class AbstractDoctrineWithMigrationTestCase extends WebTestCase
{

    protected static function createClient(array $options = [], array $server = []): KernelBrowser
    {
        $client =  parent::createClient($options, $server);
        $application = new Application(static::$kernel  );

       // $command = $application->find('doctrine:migrations:migrate ');
        $command = $application->find('doctrine:database:create');
        $commandTester = new CommandTester($command);
        $commandTester->execute( ['--no-interaction' => true]);


        $commandTester->assertCommandIsSuccessful();
        $output = $commandTester->getDisplay();

        $command = $application->find('doctrine:migrations:migrate');
        $commandTester = new CommandTester($command);
        $commandTester->execute( ['--no-interaction' => true]);


        $commandTester->assertCommandIsSuccessful();

        // the output of the command in the console
        $output = $commandTester->getDisplay();

        return $client;
    }

    protected function tearDown(): void
    {
        $application = new Application(static::$kernel  );
        $command = $application->find('doctrine:database:drop');
        $commandTester = new CommandTester($command);
        $commandTester->execute( ['--no-interaction' => true,
            '--force' =>true]);

        $commandTester->assertCommandIsSuccessful();
        // the output of the command in the console
        $output = $commandTester->getDisplay();

        parent::tearDown();


    }
}