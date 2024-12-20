<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Application;

use LaSalle\GroupZero\Logging\Domain\Model\Aggregate\LogEntry;
use LaSalle\GroupZero\Logging\Domain\Model\Repository\LogEntryRepository;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogLevel;

final class GetLogEntriesByEnvironment implements ApplicationService
{
    public function __construct(private LogEntryRepository $repository)
    {
    }

    /**
     * @return LogEntry[]
     */
    public function __invoke(GetLogEntriesByEnvironmentRequest $request): array
    {
        $domainLogLevels = 0 === count($request->levels()) ?
            LogLevel::all() :
            $this->mapToDomainLevels(...$request->levels());

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
