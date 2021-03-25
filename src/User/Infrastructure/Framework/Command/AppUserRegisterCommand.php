<?php

namespace LaSalle\GroupZero\User\Infrastructure\Framework\Command;

use LaSalle\GroupZero\User\Application\RegisterUser;
use LaSalle\GroupZero\User\Application\RegisterUserRequest;
use LaSalle\GroupZero\User\Domain\Model\Aggregate\User;
use LaSalle\GroupZero\User\Infrastructure\Framework\Form\Model\RegistrationFormModel;
use LaSalle\GroupZero\User\Infrastructure\Framework\Form\RegistrationFormType;
use Matthias\SymfonyConsoleForm\Console\Helper\FormHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

class AppUserRegisterCommand extends Command
{
    protected static $defaultName = 'app:user:register';

    public function __construct(private RegisterUser $registerUser)
    {
        parent::__construct();
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

        /* @var User $user */
        ($this->registerUser)(new RegisterUserRequest($model->email(), $model->plainPassword()));

        $io->writeln(
            <<<EOF
Registered successfully the user <comment>{$user->email()}</comment> identified by <comment>{$user->id()}</comment>
EOF
        );

        return 0;
    }
}
