<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Persistence\Filesystem;

use DateTimeImmutable;
use LaSalle\GroupZero\Logging\Domain\Model\Aggregate\LogEntry;
use LaSalle\GroupZero\Logging\Domain\Model\Repository\LogEntryRepository;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogLevel;
use LaSalle\GroupZero\Logging\Infrastructure\Persistence\Filesystem\Finder\LogFileFinder;
use LaSalle\GroupZero\Logging\Infrastructure\Persistence\Filesystem\Reader\LogFileReader;

final class LogEntryFilesystemRepository implements LogEntryRepository
{
    /** @var LogFileFinder */
    private $finder;

    /** @var LogFileReader */
    private $reader;

    public function __construct(LogFileFinder $finder, LogFileReader $reader)
    {
        $this->finder = $finder;
        $this->reader = $reader;
    }

    /**
     * {@inheritdoc}
     */
    public function findAllByEnvironment(string $environment, LogLevel ...$levels): array
    {
        $files = $this->finder->find($environment);

        $normalizedLogEntries = $this
            ->reader
            ->read($files);

        $filteredLogEntries = $this->filterLogEntries($normalizedLogEntries, ...$levels);

        return $this->mapToDomain($environment, $filteredLogEntries);
    }

    private function filterLogEntries(array $normalizedLogEntries, LogLevel ...$levels): array
    {
        $levels = array_map('strtoupper', $levels);

        return array_filter(
            $normalizedLogEntries,
            static function (array $logEntry) use ($levels) {
                return in_array($logEntry['level_name'], $levels, true);
            }
        );
    }

    private function mapToDomain($environment, array $normalizedLogEntries): array
    {
        $entries = [];

        foreach ($normalizedLogEntries as $normalizedLogEntry) {
            $entries[] = new LogEntry(
                $normalizedLogEntry['extra']['id'],
                $environment,
                new LogLevel(strtolower($normalizedLogEntry['level_name'])),
                $normalizedLogEntry['message'],
                new DateTimeImmutable($normalizedLogEntry['datetime']['date'])
            );
        }

        return $entries;
    }
}
