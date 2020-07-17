<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Framework\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AppGreetingCommand extends Command
{
    protected static $defaultName = 'app:greeting';

    protected function configure()
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Your name')
            ->addArgument('surname', InputArgument::OPTIONAL, 'Optionally you can provide your surname')
            ->addOption('uppercase', 'u', InputOption::VALUE_NONE, 'Whether to display the greeting in uppercase or not');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $text = 'Hi '.$input->getArgument('name');

        if ($surname = $input->getArgument('surname')) {
            $text .= ' '.$surname;
        }

        if ($input->getOption('uppercase')) {
            $text = strtoupper($text);
        }

        $output->writeln($text);

        return 0;
    }
}
