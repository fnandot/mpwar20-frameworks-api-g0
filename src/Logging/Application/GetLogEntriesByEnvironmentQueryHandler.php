<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Application;

use LaSalle\GroupZero\Logging\Domain\Model\Aggregate\LogEntry;
use LaSalle\GroupZero\Logging\Domain\Model\Repository\LogEntryRepository;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogLevel;

final class GetLogEntriesByEnvironmentQueryHandler
{
    /** @var LogEntryRepository */
    private $repository;

    public function __construct(LogEntryRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return LogEntry[]
     */
    public function __invoke(GetLogEntriesByEnvironmentQuery $query): array
    {
        $domainLogLevels = !$query->levels() ?
            LogLevel::all() :
            $this->mapToDomainLevels(...$query->levels());

        return $this
            ->repository
            ->findAllByEnvironment($query->environment(), ...$domainLogLevels);
    }

    /**
     * @return LogLevel[]
     */
    private function mapToDomainLevels(string ...$levels): array
    {
        $domainLogLevels = [];

        foreach ($levels as $level) {
            $domainLogLevels[] = LogLevel::fromString($level);
        }

        return $domainLogLevels;
    }
}
