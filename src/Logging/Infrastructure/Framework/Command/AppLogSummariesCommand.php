<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Framework\Command;

use LaSalle\GroupZero\Logging\Application\GetLogSummariesByEnvironment;
use LaSalle\GroupZero\Logging\Application\GetLogSummariesByEnvironmentRequest;
use LaSalle\GroupZero\Logging\Application\LogSummaryResponse;
use LaSalle\GroupZero\Logging\Domain\Model\Aggregate\LogSummary;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogLevel;
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

    protected static $defaultName = 'app:log:summaries';

    /** @var GetLogSummariesByEnvironment */
    private $getLogSummariesByEnvironment;

    public function __construct(GetLogSummariesByEnvironment $getLogSummariesByEnvironment)
    {
        parent::__construct();
        $this->getLogSummariesByEnvironment = $getLogSummariesByEnvironment;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Dump logs based on the environment')
            ->setHelp('This command allows you to dump logs based on the environment');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $environment = $io->ask('Which environment do you want to generate a summary?', $_ENV['APP_ENV']);

        $question = new ChoiceQuestion(
            'Please, specify for which levels you want to generate a summary:',
            LogLevel::$allowedValues,
            implode(',', LogLevel::$allowedValues)
        );

        $question->setMultiselect(true);

        $levels = $this
            ->getHelper('question')
            ->ask($input, $output, $question);

        $summaries = ($this->getLogSummariesByEnvironment)(
            new GetLogSummariesByEnvironmentRequest(
                $environment,
                ...$levels
            )
        );

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
                    static function (LogSummaryResponse $logSummaryResponse): array {
                        return [$logSummaryResponse->level(), $logSummaryResponse->count()];
                    },
                    $summaries->summaries()
                )
            )
            ->render();

        return 0;
    }
}
