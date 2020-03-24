<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Framework\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class AppHelloWorldCommand extends Command
{
    protected static $defaultName = 'app:hello-world';

    protected function configure(): void
    {
        $this
            ->setDescription('Simply says "Hello World"')
            ->setHelp('This command allows you to say "Hello World"');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Hello world');
    }
}
