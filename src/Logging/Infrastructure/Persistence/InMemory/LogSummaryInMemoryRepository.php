<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Persistence\InMemory;

use LaSalle\GroupZero\Logging\Domain\Model\Aggregate\LogEntry;
use LaSalle\GroupZero\Logging\Domain\Model\Aggregate\LogSummary;
use LaSalle\GroupZero\Logging\Domain\Model\Repository\LogEntryRepository;
use LaSalle\GroupZero\Logging\Domain\Model\Repository\LogSummaryRepository;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogLevel;
use Ramsey\Uuid\Uuid;

final class LogSummaryInMemoryRepository implements LogSummaryRepository
{
    public function __construct(private LogEntryRepository $logEntryRepository)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function findByEnvironmentAndLevels(string $environment, LogLevel ...$levels): array
    {
        $logEntries = $this->logEntryRepository->findAllByEnvironment($environment, ...$levels);

        /** @var LogSummary[] $summaries */
        $summaries = [];

        foreach ($logEntries as $logEntry) {
            $levelName = (string)$logEntry->level();

            if (!array_key_exists($levelName, $summaries)) {
                $summaries[$levelName] = $this->buildLogSummary($environment, $logEntry);
            }

            $logSummary = $summaries[$levelName];
            $logSummary->increase();
        }

        return $summaries;
    }

    private function buildLogSummary(string $environment, LogEntry $logEntry): LogSummary
    {
        return new LogSummary((string)Uuid::uuid4(), $environment, $logEntry->level());
    }
}
