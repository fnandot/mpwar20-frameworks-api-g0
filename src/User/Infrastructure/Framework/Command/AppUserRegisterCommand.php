<?php

namespace LaSalle\GroupZero\User\Infrastructure\Framework\Command;

use LaSalle\GroupZero\User\Application\RegisterUser;
use LaSalle\GroupZero\User\Application\RegisterUserRequest;
use LaSalle\GroupZero\User\Domain\Model\Aggregate\User;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\UserRole;
use LaSalle\GroupZero\User\Infrastructure\Framework\Form\Model\RegistrationFormModel;
use LaSalle\GroupZero\User\Infrastructure\Framework\Form\RegistrationFormType;
use Matthias\SymfonyConsoleForm\Console\Helper\FormHelper;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class AppUserRegisterCommand extends Command
{
    protected static $defaultName = 'app:user:register';

    /** @var RegisterUser */
    private $registerUser;

    public function __construct(RegisterUser $registerUser)
    {
        parent::__construct();
        $this->registerUser = $registerUser;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Registers a user');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        do {
            /** @var FormHelper $formHelper */
            $formHelper = $this->getHelper('form');

            /** @var RegistrationFormModel $model */
            $model = $formHelper->interactUsingForm(new RegistrationFormType(), $input, $output);

            $io->section('Summary');

            $io
                ->write(
                    <<<EOF
You are about to register the user with email <comment>{$model->email()}</comment>
EOF
                );

            $continueExecution = $io
                ->askQuestion(new ConfirmationQuestion('Do you want to continue?'));
        } while (false === $continueExecution);

        /** @var User $user */
        $user = ($this->registerUser)(new RegisterUserRequest($model->email(), $model->plainPassword()));

        $io->writeln(
            <<<EOF
Registered successfully the user <comment>{$user->email()}</comment> identified by <comment>{$user->id()}</comment>
EOF
        );

        return 0;
    }

    private function askForId(SymfonyStyle $io): string
    {
        $question = (new Question('Please, input the user identifier'))
            ->setValidator(
                static function (string $id): string {
                    if (!Uuid::isValid($id)) {
                        throw new RuntimeException(sprintf('The id "%s" is not a valid!', $id));
                    }

                    return $id;
                }
            );

        return $io->askQuestion($question);
    }

    private function askForRoles(SymfonyStyle $io, User $user): array
    {
        $possibleRoles = array_diff(UserRole::$allowed, $user->roles());

        if (0 === count($possibleRoles)) {
            throw new RuntimeException('No possible roles to add to this user!');
        }

        $rolesQuestion = (new ChoiceQuestion(
            'Please, select the user role(s) you want to add to this user',
            $possibleRoles
        ))
            ->setMultiselect(true);

        return $io->askQuestion($rolesQuestion);
    }
}
