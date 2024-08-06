<?php

namespace App\Command\Development;

use App\Exception\Command\Security\User\CommandNotAvailableOutsideDev;
use Doctrine\ORM\EntityManagerInterface;
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
    public function __construct(private readonly EntityManagerInterface $entityManager,
        #[Autowire('%env(APP_ENV)%')] private readonly string $environment,
        #[Autowire('%kernel.project_dir%')] private readonly string $projectDir,

    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws CommandNotAvailableOutsideDev
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->isEnabled()) {
            throw new CommandNotAvailableOutsideDev($this->getName());

        }

        $dir = __DIR__;

        $filePath = $this->projectDir . '/tests/Utility/quick_sql_for_dev.sql';

        $sql = file_get_contents($filePath);

        $this->entityManager->getConnection()->executeQuery($sql);

        return Command::SUCCESS;
    }

    public function isEnabled(): bool
    {
        return in_array($this->environment, ['dev', 'test']);

    }

}