<?php

namespace Silecust\WebShop\Command\Development;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Silecust\WebShop\Exception\Command\Security\User\CommandNotAvailableOutsideDev;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;


#[AsCommand(
    name: 'silecust:dev:data-fixture:create',
    description: 'Create fake data for development and testing ',
)]
class DevDataFixtureForQuickStart extends Command
{
    public function __construct(
        private readonly EntityManagerInterface                             $entityManager,
        #[Autowire('%env(APP_ENV)%')] private readonly string               $environment,
        // To be set in phpunit.xml.dist variable -> APP_TEST_SQL_LOCATION
        #[Autowire('%env(APP_TEST_SQL_LOCATION)%')] private readonly string $sqlScriptLocation,
        #[Autowire('%kernel.project_dir%')] private readonly string         $projectDir,

    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws CommandNotAvailableOutsideDev
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->isEnabled()) {
            throw new CommandNotAvailableOutsideDev($this->getName());

        }

        $filePath = $this->projectDir . $this->sqlScriptLocation;

        $sql = file_get_contents($filePath);

        $this->entityManager->getConnection()->executeQuery($sql);

        return Command::SUCCESS;
    }

    public function isEnabled(): bool
    {
        return in_array($this->environment, ['dev', 'test']);

    }

}