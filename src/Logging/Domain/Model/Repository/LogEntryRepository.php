<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Domain\Model\Repository;

use LaSalle\GroupZero\Logging\Domain\Model\Aggregate\LogEntry;
use LaSalle\GroupZero\Logging\Domain\Model\PaginatedLogEntryCollection;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogLevel;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\Pagination;

interface LogEntryRepository
{
    /** @return PaginatedLogEntryCollection|LogEntry[] */
    public function findByEnvironmentPaginated(
        string $environment,
        Pagination $pagination
    ): PaginatedLogEntryCollection;

    /** @return array|LogEntry[] */
    public function findAllByEnvironment(string $environment, LogLevel ...$levels): array;

    public function save(LogEntry $logEntry): void;
}
