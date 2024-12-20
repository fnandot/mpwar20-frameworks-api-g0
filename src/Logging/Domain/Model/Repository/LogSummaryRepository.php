<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Domain\Model\Repository;

use LaSalle\GroupZero\Logging\Domain\Model\Aggregate\LogSummary;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogLevel;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogSummaryId;

interface LogSummaryRepository
{
    public function find(LogSummaryId $id): ?LogSummary;

    /**
     * @return LogSummary[]
     */
    public function findByEnvironmentAndLevels(string $environment, LogLevel ...$levels): array;

    public function findOneByEnvironmentAndLevel(string $environment, LogLevel $level): ?LogSummary;

    public function save(LogSummary $logSummary): void;
}
