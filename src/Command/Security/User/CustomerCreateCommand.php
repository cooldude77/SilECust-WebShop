<?php
/** @noinspection PhpArithmeticTypeCheckInspection */

namespace App\Command\Security\User;

use App\Exception\Command\Security\User\CommandNotAvailableOutsideDev;
use App\Form\MasterData\Customer\DTO\CustomerDTO;
use App\Service\Component\Database\DatabaseOperations;
use App\Service\MasterData\Customer\Mapper\CustomerDTOMapper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[AsCommand(
    name: 'silecust:customer:sample:create',
    description: 'Create a sample customer',
)]
class CustomerCreateCommand extends Command
{
    public function __construct(private readonly CustomerDTOMapper $customerDTOMapper,
        private readonly DatabaseOperations $databaseOperations,
        #[Autowire('%env(APP_ENV)%')] private readonly string $environment
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
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        if (!$this->isEnabled()) {
            throw new CommandNotAvailableOutsideDev($this->getName());

        }

        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');

        $question = new Question('Enter Email for customer:', null);

        $question->setValidator(function (string $answer): string {
            if (!filter_var($answer, FILTER_VALIDATE_EMAIL)) {
                throw new \RuntimeException(
                    'The Email is not valid'
                );
            }

            return $answer;
        });

        $email = $helper->ask($input, $output, $question);


        $question = new Question('Enter First Name :', null);

        $firstName = $helper->ask($input, $output, $question);


        $question = new Question('Enter Last Name :', null);

        $lastName = $helper->ask($input, $output, $question);

        $question = new Question('Enter Secure Password:', null);

        $password = $helper->ask($input, $output, $question);


        $customerDTO = new CustomerDTO();
        $customerDTO->firstName = $firstName;
        $customerDTO->email = $email;
        $customerDTO->lastName = $lastName;
        $customerDTO->plainPassword = $password;

        $customer = $this->customerDTOMapper->mapToEntityForCreate($customerDTO);

        $this->databaseOperations->save($customer);

        /** @noinspection PhpArithmeticTypeCheckInspection */
        $io = new SymfonyStyle($input, $output);


        $io->success(
            'Customer created successfully. You can log into the app now as customer'
        );

        return Command::SUCCESS;
    }

    public function isEnabled(): bool
    {
        // todo: How to eliminate 'test' value here and yet get it tested
        return in_array($this->environment, ['dev', 'test']);
    }

}
