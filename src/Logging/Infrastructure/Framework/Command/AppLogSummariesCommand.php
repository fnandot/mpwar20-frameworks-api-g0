<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Framework\Command;

use LaSalle\GroupZero\Logging\Application\GetLogEntriesByEnvironment;
use LaSalle\GroupZero\Logging\Application\GetLogEntriesByEnvironmentRequest;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogLevel;
use LaSalle\GroupZero\Logging\Infrastructure\Persistence\Filesystem\Finder\LogRotatingFileFinder;
use LaSalle\GroupZero\Logging\Infrastructure\Persistence\Filesystem\LogEntryFilesystemRepository;
use LaSalle\GroupZero\Logging\Infrastructure\Persistence\Filesystem\Parser\JsonLogParser;
use LaSalle\GroupZero\Logging\Infrastructure\Persistence\Filesystem\Reader\LogFileReader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

final class AppLogSummariesCommand extends Command implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    protected static string $defaultName = 'app:log:summaries';

    protected function configure(): void
    {
        $this
            ->setDescription('Dump logs based on the environment')
            ->setHelp('This command allows you to dump logs based on the environment');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $environment = $io->ask('Which environment do you want to generate a summary?', getenv('APP_ENV'));

        $question = new ChoiceQuestion(
            'Please, specify for which levels you want to generate a summary:',
            LogLevel::$allowedValues,
            implode(',', LogLevel::$allowedValues)
        );

        $question->setMultiselect(true);

        $levels = $this
            ->getHelper('question')
            ->ask($input, $output, $question);

        $summaries = $this->summarize($environment, ...$levels);

        if (0 === count($summaries)) {
            $io->note(
                sprintf(
                    'Hey! No log summaries were found for environment "%s" and level(s) "%s"!',
                    $environment,
                    implode(',', $levels)
                )
            );

            return 0;
        }

        $io->section(sprintf('Log summaries for "%s" environment', $environment));

        (new Table($io))
            ->setHeaders(['Level', 'Count'])
            ->setRows(
                array_map(
                    static function (string $level, int $count): array {
                        return [$level, $count];
                    },
                    array_keys($summaries),
                    $summaries
                )
            )
            ->render();

        return 0;
    }

    private function buildGetLogEntriesByEnvironment(): GetLogEntriesByEnvironment
    {
        $logs = $this->container->getParameter('kernel.logs_dir');

        return new GetLogEntriesByEnvironment(
            new LogEntryFilesystemRepository(
                new LogRotatingFileFinder($logs),
                new LogFileReader(
                    new JsonLogParser()
                )
            )
        );
    }

    private function summarize(string $environment, string ...$levels): array
    {
        $logEntries = $this->buildGetLogEntriesByEnvironment()(
            new GetLogEntriesByEnvironmentRequest(
                $environment,
                ...$levels
            )
        );

        $summaries = [];

        foreach ($logEntries as $logEntry) {
            $levelName             = (string) $logEntry->level();
            $summaries[$levelName] = ($summaries[$levelName] ?? 0) + 1;
        }

        return $summaries;
    }
}
