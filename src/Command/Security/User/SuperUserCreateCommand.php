<?php
/** @noinspection PhpArithmeticTypeCheckInspection */

namespace Silecust\WebShop\Command\Security\User;

use Silecust\WebShop\Form\MasterData\Employee\DTO\EmployeeDTO;
use Silecust\WebShop\Service\Component\Database\DatabaseOperations;
use Silecust\WebShop\Service\MasterData\Employee\Mapper\EmployeeDTOMapper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'silecust:user:super:create',
    description: 'Create a super user for your website administration',
)]
class SuperUserCreateCommand extends Command
{
    public function __construct(private readonly EmployeeDTOMapper $employeeDTOMapper,
        private readonly DatabaseOperations $databaseOperations,
   private readonly UserPasswordHasherInterface $userPasswordHasher
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');

        $question = new Question('Enter Email for super user:', null);

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


        $employeeDTO = new EmployeeDTO();
        $employeeDTO->firstName = $firstName;
        $employeeDTO->email = $email;
        $employeeDTO->lastName = $lastName;

        $employee = $this->employeeDTOMapper->mapToEntityForCreate($employeeDTO);


        // the password for mapping user creates a random password
        // To use the password entered by user, set the password again
        $user = $employee->getUser();
        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                $password
            )
        );
       // change the role
        $user->setRoles(['ROLE_SUPER_ADMIN']);

        $this->databaseOperations->save($employee);

        /** @noinspection PhpArithmeticTypeCheckInspection */
        $io = new SymfonyStyle($input, $output);


        $io->success(
            'Super User created successfully. You can log into the app now'
        );

        return Command::SUCCESS;
    }
}
