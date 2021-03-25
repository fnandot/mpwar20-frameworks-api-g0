<?php

namespace LaSalle\GroupZero\User\Infrastructure\Framework\Command;

use LaSalle\GroupZero\User\Application\GetUser;
use LaSalle\GroupZero\User\Application\GetUserRequest;
use LaSalle\GroupZero\User\Application\RemoveUserRole;
use LaSalle\GroupZero\User\Application\RemoveUserRoleRequest;
use LaSalle\GroupZero\User\Domain\Model\Aggregate\User;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class AppUserRemoveRoleCommand extends Command
{
    protected static $defaultName = 'app:user:remove-role';

    public function __construct(private GetUser $getUser, private RemoveUserRole $removeUserRole)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Removes a role(s) from a user');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        do {
            $id = $this->askForId($io);

            $user = ($this->getUser)(new GetUserRequest($id));

            $roles = $this->askForRoles($io, $user);

            $allRoles = implode(',', $roles);

            $io->section('Summary');

            $io
                ->write(
                    <<<EOF
You are about to remove from the user <comment>${id}</comment> the next role(s): <comment>${allRoles}</comment>
EOF
                );

            $continueExecution = $io
                ->askQuestion(new ConfirmationQuestion('Do you want to continue?'));
        } while (false === $continueExecution);

        foreach ($roles as $role) {
            ($this->removeUserRole)(new RemoveUserRoleRequest($id, $role));
            $io->writeln(sprintf('Added role <comment>%s</comment> to user <comment>%s</comment>', $role, $id));
        }

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
        $rolesQuestion = (new ChoiceQuestion(
            'Please, select the user role(s) you want to remove from this user',
            $user->roles()
        ))
            ->setMultiselect(true);

        return $io->askQuestion($rolesQuestion);
    }
}
