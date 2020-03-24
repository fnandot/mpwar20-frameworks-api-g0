<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Persistence\Filesystem;

use LaSalle\GroupZero\Logging\Domain\Model\Aggregate\LogEntry;
use LaSalle\GroupZero\Logging\Domain\Model\Repository\LogEntryRepository;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogLevel;

/**
 * This repository does nothing.
 */
final class LogEntryNoOpRepository implements LogEntryRepository
{
    /**
     * {@inheritdoc}
     */
    public function findAllByEnvironment(string $environment, LogLevel ...$levels): array
    {
        return [];
    }

    public function save(LogEntry $logEntry): void
    {
    }
}
