<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Domain\Model\Repository;

use LaSalle\GroupZero\Logging\Domain\Model\Aggregate\LogSummary;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogLevel;

interface LogSummaryRepository
{
    /**
     * @return LogSummary[]
     */
    public function findByEnvironmentAndLevels(string $environment, LogLevel ...$levels): array;
}
