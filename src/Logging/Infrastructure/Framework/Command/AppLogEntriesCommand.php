<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Framework\Command;

use LaSalle\GroupZero\Logging\Application\GetLogEntriesByEnvironment;
use LaSalle\GroupZero\Logging\Application\GetLogEntriesByEnvironmentRequest;
use LaSalle\GroupZero\Logging\Infrastructure\Persistence\Filesystem\Finder\LogRotatingFileFinder;
use LaSalle\GroupZero\Logging\Infrastructure\Persistence\Filesystem\LogEntryFilesystemRepository;
use LaSalle\GroupZero\Logging\Infrastructure\Persistence\Filesystem\Parser\JsonLogParser;
use LaSalle\GroupZero\Logging\Infrastructure\Persistence\Filesystem\Reader\LogFileReader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class AppLogEntriesCommand extends Command
{
    protected static $defaultName = 'app:log:entries';

    /** @var GetLogEntriesByEnvironment */
    private $getLogEntriesByEnvironment;

    public function __construct(GetLogEntriesByEnvironment $getLogEntriesByEnvironment)
    {
        parent::__construct();
        $this->getLogEntriesByEnvironment = $getLogEntriesByEnvironment;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Dump logs based on the environment')
            ->setHelp('This command allows you to dump logs based on the environment')
            ->addArgument(
                'environment',
                InputArgument::OPTIONAL,
                'Only show the logs in the given environment',
                $_ENV['APP_ENV']
            )
            ->addOption(
                'level',
                'l',
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'Filter the levels to show'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $environment = $input->getArgument('environment');
        $levels      = $input->getOption('level');

        $entries = ($this->getLogEntriesByEnvironment)(
            new GetLogEntriesByEnvironmentRequest(
                $environment,
                ...$levels
            )
        );

        if (0 === count($entries)) {
            $io->note(sprintf('Hey! No log files were found for environment "%s"!', $environment));

            return 0;
        }

        $io->section(sprintf('Log files for "%s" environment', $environment));

        foreach ($entries as $logEntry) {
            dump($logEntry);
        }

        return 0;
    }
}
