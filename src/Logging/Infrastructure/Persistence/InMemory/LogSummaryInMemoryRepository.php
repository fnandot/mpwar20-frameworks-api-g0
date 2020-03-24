<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Persistence\InMemory;

use LaSalle\GroupZero\Logging\Domain\Model\Aggregate\LogEntry;
use LaSalle\GroupZero\Logging\Domain\Model\Aggregate\LogSummary;
use LaSalle\GroupZero\Logging\Domain\Model\Repository\LogEntryRepository;
use LaSalle\GroupZero\Logging\Domain\Model\Repository\LogSummaryRepository;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogLevel;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogSummaryId;

final class LogSummaryInMemoryRepository implements LogSummaryRepository
{
    /** @var LogEntryRepository */
    private $logEntryRepository;

    public function __construct(LogEntryRepository $logEntryRepository)
    {
        $this->logEntryRepository = $logEntryRepository;
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
            $levelName = (string) $logEntry->level();

            if (!array_key_exists($levelName, $summaries)) {
                $summaries[$levelName] = $this->buildLogSummary($environment, $logEntry);
            }

            $logSummary = $summaries[$levelName];
            $logSummary->increase();
        }

        return $summaries;
    }

    public function findOneByEnvironmentAndLevel(string $environment, LogLevel $level): ?LogSummary
    {
        return null;
    }

    public function save(LogSummary $logSummary): void
    {
    }

    private function buildLogSummary(string $environment, LogEntry $logEntry): LogSummary
    {
        return new LogSummary(LogSummaryId::generate(), $environment, $logEntry->level());
    }
}
