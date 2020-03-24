<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Application;

use LaSalle\GroupZero\Logging\Domain\Model\Aggregate\LogEntry;
use LaSalle\GroupZero\Logging\Domain\Model\Repository\LogEntryRepository;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogLevel;

final class GetLogEntriesByEnvironment
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
    public function __invoke(GetLogEntriesByEnvironmentRequest $request): array
    {
        if (0 === count($request->levels())) {
            $domainLogLevels = LogLevel::all();
        } else {
            $domainLogLevels = $this->mapToDomainLevels(...$request->levels());
        }

        return $this
            ->repository
            ->findAllByEnvironment($request->environment(), ...$domainLogLevels);
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
